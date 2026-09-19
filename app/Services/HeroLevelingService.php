<?php

namespace OGame\Services;

/**
 * Computes hero level-up XP thresholds and talent point gains.
 *
 * Pilot design for the hero/governor system (GDD section 5.5). The XP
 * threshold per level follows a power-law curve: xp_required(level) =
 * BASE_XP * level^exponent(level).
 *
 * The exponent itself is interpolated linearly from EXPONENT_START (at
 * level 1) to EXPONENT_END (at LEVEL_CAP), rather than fixed — validated
 * 2026-09-17. This gives a soft early game and a longer late game without
 * the discontinuity a hard per-bracket exponent switch would introduce
 * (a fixed-bracket version was simulated and rejected: it produced a
 * ~42-44% XP jump right at each bracket boundary, larger than any normal
 * level-to-level step).
 *
 * This is deliberately NOT a logarithmic curve on the threshold itself: a
 * logarithmic curve flattens out, which would make level-ups *faster* at
 * high level — the opposite of the "fast early, slower and slower" MMORPG
 * feel that was asked for. A power-law (or exponential) curve on the
 * absolute XP threshold is what produces that feel, while the *relative*
 * jump between two consecutive thresholds does shrink logarithmically
 * toward 1 as level grows.
 *
 * BASE_XP, EXPONENT_START and EXPONENT_END are tuning knobs, not final
 * balance — real balancing is Phase 6 per the roadmap.
 */
class HeroLevelingService
{
    private const int BASE_XP = 100;
    private const float EXPONENT_START = 1.2;
    private const float EXPONENT_END = 1.4;
    private const int LEVEL_CAP = 30;

    /**
     * XP required to go from the given level to level + 1.
     *
     * @param int $level
     * @return int
     */
    public function xpRequiredForLevel(int $level): int
    {
        return (int) round(self::BASE_XP * ($level ** $this->exponentForLevel($level)));
    }

    /**
     * The interpolated exponent for a given level, linear from
     * EXPONENT_START at level 1 to EXPONENT_END at LEVEL_CAP.
     *
     * @param int $level
     * @return float
     */
    private function exponentForLevel(int $level): float
    {
        $level = max(1, min($level, self::LEVEL_CAP));
        $progress = ($level - 1) / (self::LEVEL_CAP - 1);

        return self::EXPONENT_START + $progress * (self::EXPONENT_END - self::EXPONENT_START);
    }

    /**
     * Progress toward the next level, as a percentage (0-100).
     *
     * @param int $level
     * @param int $xp Accumulated XP within the current level.
     * @return float
     */
    public function progressPercentage(int $level, int $xp): float
    {
        $required = $this->xpRequiredForLevel($level);

        if ($required <= 0) {
            return 0.0;
        }

        return min(100.0, ($xp / $required) * 100);
    }

    /**
     * Total talent points earned by reaching the given level.
     * One point per level-up (World of Warcraft-style); a level 1 hero
     * has not leveled up yet and so has earned none.
     *
     * @param int $level
     * @return int
     */
    public function talentPointsEarned(int $level): int
    {
        return max(0, $level - 1);
    }

    /**
     * The hero level cap.
     *
     * @return int
     */
    public function levelCap(): int
    {
        return self::LEVEL_CAP;
    }
}

# CLAUDE.md

Ce fichier donne à Claude Code le contexte nécessaire pour travailler sur ce projet.

## Contexte du projet

La base technique est **OGameX** (fork de https://github.com/lanedirt/OGameX), un clone open-source d'Ogame en Laravel/PHP. Ce fork est en cours de transformation en un jeu navigateur **heroic fantasy, PvE-first**, avec un système de héros/gouverneur, une jauge de prestige d'empire, et du contenu PvE contre des bots.

**Statut actuel : entrée en Phase 3 (création de l'interface).** Les Phases 0 à 2 sont terminées : environnement Docker en place, audit d'OGameX bouclé, décision de rester sur OGameX validée le 2026-09-18. Le moteur de jeu est encore celui d'OGameX ; le GDD et le lore sont largement arrêtés (quelques **[TBD]** subsistent).

**Décision structurante — arrêtée le 2026-09-26, à connaître avant toute tâche de code :** il n'y aura **pas de reskin** d'OGameX. La couche de présentation legacy (113 609 lignes de CSS, 25 045 lignes de Blade) est **jetée**, le domaine PHP/Laravel (≈30 000 lignes de logique, 40 779 lignes de tests) est **conservé intégralement** et exposé par une API JSON, et l'interface est recréée en React. Voir [docs/decision-architecture-cible.md](docs/decision-architecture-cible.md). Ce qui est déjà en place : PHP 8.5, Laravel 13, Vite 8, Laravel Reverb.

Le travail sur le système de héros (table, arbre de talents, équipement, effets) est en cours dans le repo et servira de pilote technique à la refonte.

## Documents de conception à lire avant toute tâche liée au jeu

Ces documents dans `docs/` définissent la vision, les mécaniques, l'univers et l'architecture du projet. Les lire avant de proposer ou d'implémenter une fonctionnalité liée au gameplay, au lore, à l'architecture ou à la roadmap :

- **[docs/feuille-de-route-projet-jeu.md](docs/feuille-de-route-projet-jeu.md)** — Roadmap complète du projet par phases (cadrage, setup, audit technique, création de l'interface, mécaniques différenciantes, hébergement, contenu, ouverture publique, monétisation). À consulter pour savoir où en est le projet et ce qui vient ensuite.
- **[docs/decision-architecture-cible.md](docs/decision-architecture-cible.md)** — Décision d'architecture : ce qui est conservé du moteur OGameX et ce qui est jeté, la stack retenue (FrankenPHP/Octane, Redis + Horizon, API JSON + Sanctum, React + TypeScript, TanStack, Tailwind + shadcn/ui, Reverb), les alternatives écartées et leur motif, le chantier de la résolution temporelle, l'ordre de bascule des écrans. **À lire avant toute tâche technique**, pour ne pas rouvrir des choix déjà tranchés.
- **[docs/game-design-document.md](docs/game-design-document.md)** — Game Design Document (GDD) : piliers de gameplay, boucles courte/moyenne/longue, lexique de conversion Ogame→jeu fantasy (ressources, bâtiments, recherches, unités), systèmes détaillés (production, combat, Mode de Guerre, héros/gouverneur, prestige d'empire, craft/forge, bots PvE, alliance). Document vivant, plusieurs sections encore marquées **[TBD]**.
- **[docs/lore.md](docs/lore.md)** — Univers narratif : cosmologie (Straumar, Neuf Sphères), la Rupture (chronologie), les Vahrun, factions ennemies, races jouables, structure cartographique (Régions/Contrées/Éclats), noms encore en discussion.
- **[docs/direction-artistique-unites.md](docs/direction-artistique-unites.md)** — Briefs visuels (prompts IA générative, en anglais) pour les unités militaires du lexique, avec un préambule de style commun. Document de travail pour la Phase 3a de la roadmap (DA Midjourney).

Ces documents sont vivants et évolueront au fil du projet — s'y référer plutôt que de supposer des mécaniques ou noms non confirmés.

## Principes de travail

- **Zéro code manuel côté utilisateur** : Gregory ne code pas lui-même, tout passe par Claude Code.
- **V1 minimum jouable d'abord** : ne pas se disperser sur des fonctionnalités listées comme "plus tard" dans la roadmap tant que la V1 n'est pas cadrée/posée.
- **Les formules Ogame/OGameX de base restent héritées en V1** (production, coûts, combat) — l'équilibrage viendra en Phase 6. Ce principe concerne le **domaine**, pas l'interface : l'interface, elle, est entièrement recréée (cf. la décision d'architecture).
- **Le domaine ne se touche qu'avec raison.** `app/Services`, `app/GameMissions`, `app/GameObjects` et `app/Models` restent proches de l'upstream `lanedirt/OGameX`, qui est activement maintenu : plus on s'en éloigne, plus les correctifs de sécurité deviennent coûteux à récupérer. Les deux exceptions assumées sont le chantier de la résolution temporelle (Phase 3c) et les mécaniques différenciantes (Phase 4).
- **Aucune logique de jeu dans les contrôleurs API.** Ils valident, appellent un service, sérialisent. Toute règle manquante s'ajoute dans `app/Services` ou `app/GameMissions`, avec son test Pest — c'est ce qui garde les 1109 tests existants valides.
- **Ne rien développer en Blade qui soit destiné à durer.** Les nouvelles fonctionnalités se construisent dans la nouvelle interface React, sinon on produit de la dette à jeter. Seule l'administration serveur reste en Blade, volontairement.
- **Contenu de jeu conçu en français en premier** (noms d'archétypes, de talents, d'objets, libellés d'interface) — décidé le 2026-09-15. L'internationalisation (anglais et autres langues, infrastructure déjà en place via `resources/lang/`) viendra plus tard. Le code lui-même (colonnes, classes, machine names) reste en anglais/technique, seul le contenu affiché au joueur est en français dès maintenant.

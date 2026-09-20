# Direction artistique — Prompts visuels pour les unités

*Document de travail pour la Phase 3 de la roadmap ("Démarrer la direction artistique avec Midjourney"). Contient un brief visuel par unité militaire, prêt à coller dans Midjourney/DALL-E/Gemini/ChatGPT. Prompts en anglais (les IA génératives répondent mieux en anglais), noms d'unités en français — cohérent avec le principe "contenu français d'abord" qui concerne le contenu affiché au joueur, pas les prompts de génération d'image (cf. CLAUDE.md).*

---

## Préambule de style commun

À coller en tête de chaque prompt pour garder une cohérence visuelle sur l'ensemble du roster (ambiance déjà établie pour le système héros : "arcane mystique sombre") :

```
Dark heroic fantasy full-body character/vehicle concept art, human kingdom under siege by resurgent orc hordes, weathered practical plate and leather gear with hand-forged imperfections, muted earthy palette (iron grey, oxblood red, aged bronze) accented by a faint violet-blue arcane mana glow on runes and weapons, painterly digital illustration, dramatic single-source side lighting, clean neutral studio background, sharp readable silhouette, highly detailed
```

**Notes pratiques :**
- Une fois un premier rendu qui te plaît obtenu, utilise `--sref` (Midjourney) sur cette image pour figer le style et le réutiliser sur toutes les unités suivantes — c'est justement l'usage prévu en Phase 3 de la roadmap.
- Ces unités doivent rester lisibles **en petite icône** (listes de construction façon Ogame, pas seulement en grand artwork) — privilégie une silhouette distincte et un point focal clair (arme, posture) par unité plutôt que des détails fins qui disparaîtront à petite taille.
- Ajoute tes propres paramètres techniques (`--ar 1:1` ou `16:9`, `--v`, `--stylize`, etc.) selon l'outil utilisé.

---

## 🦶 Unités au sol

### Soldat *(chair à canon)*
```
Common human foot soldier, cheap conscript-tier infantry, light padded leather armor with a partial rusted mail vest, round wooden shield banded in iron, short sword or simple spear, plain unremarkable tabard, tired weary expression, meant to look replaceable and numerous rather than heroic, dirt and travel-worn gear
```

### Garde *(tank de ligne)*
```
Heavily armored human line infantry, thick full plate armor plating, large tower shield covering most of the body, halberd or broadsword, closed great helm hiding the face, wide stable battle stance, dented and battle-scarred armor implying countless frontline clashes, imposing bulky silhouette
```

### Rôdeur des ombres *(puissant mais mono-cible)*
```
Elite human assassin-warrior, fitted dark leather-and-blackened-steel armor, tattered dark cloak, dual curved blades or one massive two-handed sword, lower face hidden by a wrapped mask, faint dark violet magical energy wisping off the weapon edge, crouched predatory stance, singled out for a decisive killing strike rather than fighting a crowd
```

### Piquier des Marches *(anti-monté)*
```
Border-guard human pikeman, medium banded armor, very long hooked pike/halberd designed to unhorse cavalry, small round buckler, planted wide defensive stance angled forward, rugged frontier gear in earthy browns and greens, weathered cloak
```

### Mage de Combat *(anti-unités légères)*
```
Human battle mage, lightly armored robes reinforced with glowing rune-etched plates, arcane staff or floating orb channeling violet-blue mana energy, a glowing rune circle forming at their feet, dynamic spellcasting pose with one arm outstretched, slender frame contrasted by an intense magical aura, wind-swept robes
```

### Franc-Archer *(anti-mobilité)*
```
Human ranger archer, light leather scout gear with a hooded cloak, longbow or crossbow drawn, quiver full of rune-tipped arrows, alert crouched aiming stance, muted forest-camouflage tones, agile and lightly equipped
```

---

## 🐎 Unités montées

### Cavalier *(mobile/anti-unités)*
```
Human cavalry rider on a fast war-horse, medium plate armor, one-handed lance or sword, small round shield, kingdom-colored tabard or banner streaming behind, dynamic mid-charge galloping pose, dust kicked up beneath the horse's hooves
```

### Paladin *(tank d'élite)*
```
Heavily armored human paladin knight, ornate full plate armor engraved with golden sacred motifs, mounted on a massive barded warhorse, large heraldic kite shield, heavy warhammer or blessed longsword, faint golden holy light aura around the figure, noble imposing bearing
```

### Pourfendeur *(duelliste/anti-élite)*
```
Lone human knight duelist in distinctive dark tarnished armor, riding an agile lean warhorse, two-handed sword or a broken lance held ready, torn cape flowing, confrontational forward-leaning stance, marked as a specialist hunter of enemy knights and elite riders rather than common troops
```

### Chevaucheur des Plaines *(harceleur)*
```
Light human skirmish cavalry rider, minimal leather armor for speed, riding a lean fast nervous horse, short recurve bow or throwing axes, leaning forward in a hit-and-run raiding posture, dusty muted colors, no heavy shield or armor
```

---

## 🏰 Engins de siège

### Brise-Rempart *(anti-défenses)*
```
Massive wheeled siege engine, thick reinforced iron plating, large mounted arcane cannon or catapult arm aimed at fortifications, small visible crew operating it, slow and imposing scale, clearly built to demolish fixed structures rather than fight infantry, smoke or mana-charged glow from its main weapon
```

### Catapulte de Rupture *(anti-fortification)*
```
Great siege catapult or battering ram reinforced with glowing arcane runes carved into wood and iron, heavy swinging arm or metal-capped ram head at the front, hauled by draft beasts or a small crew, massive scale designed to shatter gates and walls, weathered wood and iron construction
```

---

## Points encore ouverts

- [ ] Palette de couleurs définitive (royaume du joueur vs factions ennemies) — à figer une fois les premiers essais Midjourney validés
- [ ] Reaper (classe héros General, cf. GDD 5.5) — pas encore de nom/description, à traiter avec le reste du système héros
- [ ] Unités civiles (Récupérateur, Caravane, Convoi, Éclaireur, Pionnier) et défenses statiques — pas encore de brief visuel

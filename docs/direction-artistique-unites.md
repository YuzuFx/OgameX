# Direction artistique — Prompts visuels pour les unités

*Document de travail pour la Phase 3 de la roadmap ("Démarrer la direction artistique avec Midjourney"). Contient un brief visuel par unité militaire, prêt à coller dans Midjourney/DALL-E/Gemini/ChatGPT. Prompts en anglais (les IA génératives répondent mieux en anglais), noms d'unités en français — cohérent avec le principe "contenu français d'abord" qui concerne le contenu affiché au joueur, pas les prompts de génération d'image (cf. CLAUDE.md).*

---

## Préambule de style commun

À coller en tête de chaque prompt pour garder une cohérence visuelle sur l'ensemble du roster (ambiance déjà établie pour le système héros : "arcane mystique sombre") :

```
Dark heroic fantasy full-body character/vehicle concept art, human kingdom under siege by resurgent orc hordes, weathered practical plate and leather gear with hand-forged imperfections, muted earthy palette (iron grey, oxblood red, aged bronze) accented by a faint violet-blue arcane mana glow on runes and weapons, painterly digital illustration, dramatic single-source side lighting, clean neutral studio background, sharp readable silhouette, highly detailed
```

*Traduction (vérification) :* Art conceptuel de personnage/véhicule en pied, heroic fantasy sombre, royaume humain assiégé par des hordes d'orcs résurgentes, équipement pratique et usé en plaques et cuir avec des imperfections de forge artisanale, palette terreuse sourde (gris fer, rouge sang séché, bronze vieilli) rehaussée d'une faible lueur arcanique violet-bleu sur les runes et les armes, illustration numérique façon peinture, éclairage dramatique à source unique de côté, fond de studio neutre, silhouette nette et lisible, très détaillé

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
*Traduction :* Simple fantassin humain, infanterie de conscription bon marché, armure de cuir légère matelassée avec une cotte de mailles partielle rouillée, bouclier rond en bois cerclé de fer, épée courte ou lance simple, tabard quelconque sans distinction, expression fatiguée, doit paraître remplaçable et nombreux plutôt qu'héroïque, équipement sale et usé par la route

### Garde *(tank de ligne)*
```
Heavily armored human line infantry, thick full plate armor plating, large tower shield covering most of the body, halberd or broadsword, closed great helm hiding the face, wide stable battle stance, dented and battle-scarred armor implying countless frontline clashes, imposing bulky silhouette
```
*Traduction :* Infanterie de ligne humaine lourdement blindée, épaisse armure de plates complète, grand bouclier tour couvrant la majeure partie du corps, hallebarde ou épée large, grand heaume fermé cachant le visage, posture de combat large et stable, armure cabossée et marquée de cicatrices de bataille suggérant d'innombrables affrontements en première ligne, silhouette imposante et massive

### Rôdeur des ombres *(puissant mais mono-cible)*
```
Elite human assassin-warrior, fitted dark leather-and-blackened-steel armor, tattered dark cloak, dual curved blades or one massive two-handed sword, lower face hidden by a wrapped mask, faint dark violet magical energy wisping off the weapon edge, crouched predatory stance, singled out for a decisive killing strike rather than fighting a crowd
```
*Traduction :* Guerrier-assassin humain d'élite, armure ajustée de cuir et d'acier noirci, cape sombre en lambeaux, deux lames courbes ou une immense épée à deux mains, bas du visage caché par un tissu enroulé, faible énergie magique violet sombre s'échappant du tranchant de l'arme, posture accroupie et prédatrice, ciblé pour un coup fatal décisif plutôt que pour combattre une foule

### Piquier des Marches *(anti-monté)*
```
Border-guard human pikeman, medium banded armor, very long hooked pike/halberd designed to unhorse cavalry, small round buckler, planted wide defensive stance angled forward, rugged frontier gear in earthy browns and greens, weathered cloak
```
*Traduction :* Piquier humain de garde-frontière, armure moyenne à bandes de métal, très longue pique/hallebarde crochetée conçue pour désarçonner la cavalerie, petit bouclier rond (targe), posture défensive large et plantée, orientée vers l'avant, équipement rustique de frontière dans des tons terreux bruns et verts, cape usée

### Mage de Combat *(anti-unités légères)*
```
Human battle mage, lightly armored robes reinforced with glowing rune-etched plates, arcane staff or floating orb channeling violet-blue mana energy, a glowing rune circle forming at their feet, dynamic spellcasting pose with one arm outstretched, slender frame contrasted by an intense magical aura, wind-swept robes
```
*Traduction :* Mage de combat humain, robes légèrement blindées renforcées de plaques gravées de runes lumineuses, bâton arcanique ou orbe flottant canalisant une énergie mana violet-bleu, un cercle runique lumineux se formant à ses pieds, posture dynamique de lancement de sort avec un bras tendu, silhouette fine contrastant avec une aura magique intense, robes soulevées par le vent

### Franc-Archer *(anti-mobilité)*
```
Human ranger archer, light leather scout gear with a hooded cloak, longbow or crossbow drawn, quiver full of rune-tipped arrows, alert crouched aiming stance, muted forest-camouflage tones, agile and lightly equipped
```
*Traduction :* Archer-rôdeur humain, équipement léger de cuir avec une cape à capuche, arc long ou arbalète bandé(e), carquois rempli de flèches à pointe runique, posture accroupie et alerte en visée, tons sourds de camouflage forestier, agile et légèrement équipé

---

## 🐎 Unités montées

### Cavalier *(mobile/anti-unités)*
```
Human cavalry rider on a fast war-horse, medium plate armor, one-handed lance or sword, small round shield, kingdom-colored tabard or banner streaming behind, dynamic mid-charge galloping pose, dust kicked up beneath the horse's hooves
```
*Traduction :* Cavalier humain sur un cheval de guerre rapide, armure moyenne, lance ou épée à une main, petit bouclier rond, tabard ou bannière aux couleurs du royaume flottant derrière lui, posture dynamique en pleine charge au galop, poussière soulevée sous les sabots du cheval

### Paladin *(tank d'élite)*
```
Heavily armored human paladin knight, ornate full plate armor engraved with golden sacred motifs, mounted on a massive barded warhorse, large heraldic kite shield, heavy warhammer or blessed longsword, faint golden holy light aura around the figure, noble imposing bearing
```
*Traduction :* Chevalier paladin humain lourdement blindé, armure de plates complète et ornée gravée de motifs sacrés dorés, monté sur un imposant destrier caparaçonné, grand bouclier héraldique en écu, lourd marteau de guerre ou épée longue bénie, faible aura de lumière dorée et sacrée autour de la silhouette, prestance noble et imposante

### Pourfendeur *(duelliste/anti-élite)*
```
Lone human knight duelist in distinctive dark tarnished armor, riding an agile lean warhorse, two-handed sword or a broken lance held ready, torn cape flowing, confrontational forward-leaning stance, marked as a specialist hunter of enemy knights and elite riders rather than common troops
```
*Traduction :* Chevalier duelliste humain solitaire dans une armure sombre et ternie distinctive, monté sur un destrier agile et élancé, épée à deux mains ou lance brisée tenue prête, cape déchirée flottante, posture de confrontation penchée vers l'avant, marqué comme un chasseur spécialisé d'autres chevaliers et cavaliers d'élite ennemis plutôt que de troupes communes

### Chevaucheur des Plaines *(harceleur)*
```
Light human skirmish cavalry rider, minimal leather armor for speed, riding a lean fast nervous horse, short recurve bow or throwing axes, leaning forward in a hit-and-run raiding posture, dusty muted colors, no heavy shield or armor
```
*Traduction :* Cavalier léger humain d'escarmouche, armure de cuir minimale pour la vitesse, monté sur un cheval fin, rapide et nerveux, arc court recourbé ou haches de jet, penché vers l'avant dans une posture de raid éclair (frappe-et-fuite), couleurs sourdes et poussiéreuses, sans bouclier ni armure lourde

---

## 🏰 Engins de siège

### Brise-Rempart *(anti-défenses)*
```
Massive wheeled siege engine, thick reinforced iron plating, large mounted arcane cannon or catapult arm aimed at fortifications, small visible crew operating it, slow and imposing scale, clearly built to demolish fixed structures rather than fight infantry, smoke or mana-charged glow from its main weapon
```
*Traduction :* Immense engin de siège à roues, plaques de fer épaisses renforcées, grand canon arcanique ou bras de catapulte monté visant des fortifications, petit équipage visible l'actionnant, échelle lente et imposante, clairement construit pour démolir des structures fixes plutôt que combattre l'infanterie, fumée ou lueur chargée de mana s'échappant de son arme principale

### Catapulte de Rupture *(anti-fortification)*
```
Great siege catapult or battering ram reinforced with glowing arcane runes carved into wood and iron, heavy swinging arm or metal-capped ram head at the front, hauled by draft beasts or a small crew, massive scale designed to shatter gates and walls, weathered wood and iron construction
```
*Traduction :* Grande catapulte de siège ou bélier renforcé de runes arcaniques lumineuses gravées dans le bois et le fer, lourd bras oscillant ou tête de bélier à embout métallique à l'avant, tracté(e) par des bêtes de trait ou un petit équipage, échelle massive conçue pour briser portes et remparts, construction en bois et fer usés par le temps

---

## Points encore ouverts

- [ ] Palette de couleurs définitive (royaume du joueur vs factions ennemies) — à figer une fois les premiers essais Midjourney validés
- [ ] Reaper (classe héros General, cf. GDD 5.5) — pas encore de nom/description, à traiter avec le reste du système héros
- [ ] Unités civiles (Récupérateur, Caravane, Convoi, Éclaireur, Pionnier) et défenses statiques — pas encore de brief visuel

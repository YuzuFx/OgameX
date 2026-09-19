# Game Design Document — [Nom du jeu à définir]

*Statut : document vivant, en cours de construction (Phase 0). Les sections marquées **[TBD]** restent à trancher ensemble.*

---

## 1. Pitch / Vision

**[TBD]** — Résumé en 2-3 phrases : quoi, pour qui, ce qui le différencie.

*Piste de travail basée sur nos échanges : un jeu de gestion d'empire façon Ogame, transposé en heroic fantasy, orienté PvE-first — les joueurs développent leur royaume, envoient des héros-gouverneurs en mission contre des forces hostiles (PvE), et progressent en coopération plutôt qu'en confrontation directe entre joueurs.*

---

## 2. Piliers de gameplay

Les principes non-négociables qui guident toute décision de design future.

- **PvE-first, PvP strictement consenti** : le PvP n'est jamais subi. Voir le Mode de Guerre (section 5.4) pour le mécanisme précis — invulnérabilité totale par défaut, exposition uniquement sur activation volontaire et réciproque.
- **Accessible / noob-friendly** : pas de perte permanente et irréversible (héros, ressources critiques). L'échec coûte du temps, jamais une remise à zéro.
- **Coopération plutôt que compétition** : les mécaniques d'alliance renforcent la cohésion de groupe via des objectifs PvE communs, pas via la domination d'autres joueurs.
- **[TBD]** — autres piliers à définir (ex : rythme de jeu voulu — plutôt actif/réactif ou plutôt lent/asynchrone comme Ogame classique ?)

---

## 3. Boucle de gameplay principale

*Rythme visé : hybride — une routine quotidienne courte, avec des objectifs qui mûrissent sur plusieurs jours. Durée des missions de héros variable selon la difficulté/distance de la cible (façon trajet de flotte Ogame).*

### Boucle courte (session quotidienne, quelques minutes)

- Vérifier l'état de la production de ressources sur ses villes, ajuster si besoin
- Consulter la file de construction/recherche en cours, lancer la suivante si elle est libre
- Vérifier les héros disponibles (revenus de mission ou en attente) et en assigner de nouvelles missions PvE contre des bots
- Lire les rapports reçus (résultat de mission, pillage, événement)
- Jeter un œil à la jauge de prestige d'empire et l'ajuster mentalement dans ses choix de risque du jour

### Boucle moyenne (sur plusieurs jours)

- Voir aboutir une recherche ou un palier de bâtiment significatif
- Compléter une quête hebdomadaire narrée par un PNJ *(vérifié le 2026-09-18 : aucun système de quêtes/dialogues PNJ n'existe dans OGameX — 100 % à construire, effort élevé)*
- Accumuler assez de ressources/composants pour craft un nouvel équipement de héros
- Gérer une indisponibilité de héros prolongée (mission ratée sur une cible ambitieuse) : arbitrer entre attendre, payer une rançon/accélération, ou réorganiser temporairement sa ville sans gouverneur actif
- Participer à un événement PvE d'alliance en cours (contribution collective à un objectif à seuils)

### Boucle longue (semaines/mois)

- Débloquer un nouvel emplacement de ville via la progression de la ville principale/technologies
- Recruter et assigner un nouveau héros comme gouverneur (jusqu'à la limite de 6-7)
- Repousser ses positions vers des Contrées plus dangereuses (proches du centre de sa Région) pour un meilleur butin/loot
- Monter en tiers d'équipement de héros via le craft avancé
- Suivre les indices narratifs distillés progressivement sur le peuple englouti et la nature réelle de la Rupture

### Ce qui relie les trois boucles entre elles

La mécanique centrale qui fait tenir cet ensemble, c'est le **héros** : il consomme la boucle courte (on l'envoie en mission), influence la boucle moyenne (son indisponibilité éventuelle, son équipement), et structure la boucle longue (son recrutement, son rôle de gouverneur). C'est délibéré — plutôt que d'avoir des systèmes qui tournent indépendamment (production d'un côté, combat de l'autre, comme dans Ogame classique), le héros sert de fil conducteur qui donne une raison quotidienne de se reconnecter sans complexifier la gestion d'empire elle-même.

**[TBD]** — Point à affiner : la mécanique précise du calcul de durée d'une mission de héros (distance à la cible dans la grille Région/Contrée/Éclat, difficulté du bot ciblé, vitesse propre du héros/de sa garnison).

**Formule source identifiée le 2026-09-18** : `FleetMissionService::calculateFleetMissionDuration()` (`app/Services/FleetMissionService.php`, ligne 55), basée sur `calculateFleetMissionDistance()` (même fichier). Principe : `durée = max(round((35000 / vitesse% × √(distance × 10 / vitesse_du_plus_lent) + 10) / facteur_serveur), 1)`, où le facteur serveur diffère déjà selon le type de mission (agressive/maintien/pacifique) — mécanisme réutilisable tel quel pour distinguer par exemple une mission héros "risquée" d'une mission "prudente". Reste à trancher : adapter cette formule directement (effort *faible*, cohérent avec le principe "formules héritées"), ou construire une vraie nouvelle formule liée à la difficulté du bot plutôt qu'à une simple distance (effort *moyen*).

---

## 4. Lexique de conversion — Ogame/OGameX → [Nom du jeu]

Base de traduction thématique. Les mécaniques sous-jacentes (formules, comportements) restent héritées d'Ogame/OGameX en V1 ; seul l'habillage change ici.

### 4.1 Ressources

*Choix assumé de s'écarter du calque direct d'Ogame vers un modèle à 4 ressources stockées façon Travian — voir note technique ci-dessous.*

| Ogame/OGameX | [Nom du jeu] | Rôle |
|---|---|---|
| Métal | **Bois** | Ressource de construction de base |
| Cristal | **Fer** | Ressource de construction avancée |
| Deutérium | **Mana** | Ressource rare, alimente troupes et tech avancée |
| Énergie | **Nourriture** *(nouvelle ressource)* | Remplace le rôle structurel de l'Énergie, mais fonctionne en stock comme les trois autres — soutient population et croissance |

**Note technique (révisée)** : Bois et Fer sont des reskins directs des mécaniques Métal et Cristal d'Ogame — mêmes formules, juste renommées, effort *faible* comme pour Mana. Seule **Nourriture** est réellement nouvelle : elle prend la place structurelle de l'Énergie mais fonctionne différemment (en stock, pas en consommation temps réel) — c'est le seul point de ce système qui reste à effort *moyen* plutôt que faible.

**Principe directeur — coûts en Nourriture (ponctuels, pas d'upkeep) :**

*Règle : la Nourriture suit les êtres vivants, jamais la pierre.*

- **Unités** : coût en Nourriture systématique sur toutes, proportionnel au tier/à la taille — simule l'approvisionnement/l'équipement d'une unité pour la campagne, en un seul prélèvement (pas de consommation continue).
- **Bâtiments** : jamais de coût en Nourriture — un bâtiment est fait de matériaux (Bois/Fer/Mana), pas de rations.
- **Recherches** : coût en Nourriture uniquement pour celles directement liées à la logistique/au soutien de troupes — jamais pour la magie ou l'armement pur.
- **Héros** : probablement un coût en Nourriture au recrutement — à confirmer.

Ce principe corrige directement l'incohérence repérée chez Embercraft (bâtiments coûtant de la nourriture, certaines unités de combat n'en coûtant pas) en donnant une règle explicable en une phrase au joueur plutôt qu'une liste de cas particuliers.

**[TBD]** — Liste précise des recherches concernées, montants exacts par unité/tier : à traiter une fois le catalogue de contenu (section 6) posé, pas à deviner à l'aveugle maintenant.

### 4.2 Bâtiments

**Décidé le 2026-09-19** : la distinction technique `Building`/`Station` d'OGameX (cf. audit du 2026-09-18) n'existe pas côté joueur — tout reste "bâtiment" à l'écran, aucun impact sur le lexique. Le Terraformeur est déplacé en section 4.3 (devient une recherche, pas un bâtiment — voir détail là-bas).

| Ogame/OGameX | [Nom du jeu] | Notes |
|---|---|---|
| Mine de métal (`metal_mine`) | **Scierie** | Production de bois (Métal → Bois) |
| Mine de cristal (`crystal_mine`) | **Mine de fer** | Production de fer (Cristal → Fer) |
| Synthétiseur de deutérium (`deuterium_synthesizer`) | **Obélisque de mana** | Cf. lore section 1 |
| Centrale solaire (`solar_plant`) | **Champ de blé** | Production de nourriture — nom repéré chez Embercraft. Devient la production de la 4ᵉ ressource réelle (cf. 4.1/5.1) |
| Réacteur à fusion (`fusion_plant`) | ~~**Supprimé**~~ | **Décidé le 2026-09-19** : la mécanique Énergie/centrale électrique n'a pas sa place dans l'univers (pas de dimension steampunk voulue) — un seul bâtiment de production de Nourriture suffit (Champ de blé), ce 2ᵉ bâtiment de production d'énergie d'Ogame est retiré |
| Entrepôt de métal (`metal_store`) | **Entrepôt de bois** | |
| Entrepôt de cristal (`crystal_store`) | **Entrepôt de fer** | |
| Entrepôt de deutérium (`deuterium_store`) | **Réservoir de mana** | |
| *(nouveau, pas d'équivalent Ogame)* | **Silo de nourriture** | À créer : la Nourriture devient une ressource stockée comme les 3 autres (cf. 4.1/5.1) — vrai nouveau bâtiment, pas un reskin |
| Chantier spatial (`shipyard`) | **Forge de guerre** | Construit toutes les unités déployables |
| Laboratoire de recherche (`research_lab`) | **Académie** | |
| Base lunaire (`lunar_base`) | *(retiré)* | Aucune pertinence dans notre cosmologie, également absent d'Aleryos |
| Usine de robots (`robot_factory`) | **Atelier des Maîtres d'œuvre** | **Décidé le 2026-09-19** : reste distinct de l'usine de nanites (2 bâtiments différents dans OGameX, l'un prérequis de l'autre — conservés séparés pour la profondeur de jeu) |
| Usine de nanites (`nano_factory`) | **[TBD]** | Palier supérieur, orienté "dimension plus magique" par opposition à l'Atelier (plus artisanal/physique) — propositions à trancher : **Cercle des Enchanteurs**, **Sanctuaire des Artificiers**, ou **Atelier des Automates Arcaniques** |
| Silo de missiles (`missile_silo`) | ~~**Supprimé**~~ | Décidé le 2026-09-19 |
| Dépôt d'alliance (`alliance_depot`) | **Entrepôt de guilde** | Décidé le 2026-09-19 — sert à prolonger la durée de tenue d'une flotte ACS Défense alliée (cf. 5.9) |
| Poste de garde spatial (`space_dock`) | **Hôpital** | **Décidé le 2026-09-19** : reskin narratif complet plutôt qu'un simple renommage — l'ancienne fonction OGameX (réparation de vaisseaux / ratio épaves-débris) est remplacée par une **nouvelle mécanique de troupes blessées** : à chaque combat (attaquant comme défenseur), une partie des troupes qui auraient normalement été détruites est récupérée blessée plutôt que perdue, et revient au combattant après un délai. Plus l'Hôpital est haut niveau, plus le % de troupes secourues augmente, dans une limite raisonnable (plafond à définir). **[TBD]** — détails à trancher : % de base et % max par niveau, durée d'indisponibilité des blessés avant retour au service, si le taux diffère attaquant/défenseur, interaction avec l'explosion de coque déjà simulée par le moteur de combat (`BattleUnit::damagedHullExplosion()`) — probablement : une unité qui explose est perdue définitivement, une unité détruite sans exploser peut basculer en "blessée" selon le jet Hôpital. **Effort élevé** : mécanique de combat inédite (pas un reskin), touche au moteur de combat existant (`app/GameMissions/BattleEngine/`), pas seulement au lexique. |
| Capteur/Phalanx (`sensor_phalanx`) | **Tour de guet arcanique** | **Décidé le 2026-09-19** — conservé tel quel dans sa fonction (détection à distance des mouvements de flottes ennemies sur les Éclats voisins), seul le nom change. Effort faible, reskin direct. |
| Porte de saut (`jump_gate`) | **Téléporteur** | Décidé le 2026-09-19 — téléportation instantanée entre lunes |

### 4.3 Recherches / Technologies

**Catalogue complet vérifié le 2026-09-18** (`app/GameObjects/ResearchObjects.php`, 16 technologies). Décisions tranchées le 2026-09-19 :

| Ogame/OGameX | [Nom du jeu] | Notes |
|---|---|---|
| Technologie énergétique (`energy_technology`) | **Abondance** | Repositionnée comme techno de production de Nourriture |
| Propulsion à combustion (`combustion_drive`) | **[TBD — proposition : Marche Forcée]** | **Décidé le 2026-09-19** : les 3 propulsions sont remappées sur un triptyque **unités à pied / à cheval / engins de siège** plutôt que sur les paliers de vaisseaux d'Ogame — combustion = le palier le plus bas/rapide à obtenir → **unités à pied** |
| Propulsion à impulsion (`impulse_drive`) | **[TBD — proposition : Art Équestre]** | Palier intermédiaire → **unités à cheval** |
| Propulsion hyperspatiale (`hyperspace_drive`) | **[TBD — proposition : Ingénierie de Siège]** | Palier le plus haut/lent à obtenir → **engins de siège** (voir aussi 4.4, le Bombardier se reskinne naturellement en arme de siège : son bonus anti-défenses dans Ogame colle exactement au rôle d'un engin de siège) |
| Espionnage (`espionage_technology`) | **Espionnage** | Conservé tel quel, fonctionne déjà bien |
| Technologie informatique (`computer_technology`) | **[TBD — propositions à trancher]** | **Décidé le 2026-09-19 (orientation)** : garder cette techno (soulage le serveur en limitant les missions simultanées), mais thème logistique plutôt qu'"informatique" — propositions : **Intendance de Guerre**, **Corps Logistique**, **Chaîne de Ravitaillement** |
| ~~Technologie hyperspatiale (`hyperspace_technology`)~~ | ~~**Supprimée**~~ | **Décidé le 2026-09-19** : suppression pure et simple, sans report. Son rôle "vitesse" était de toute façon déjà couvert par le triptyque pied/cheval/siège (combustion/impulsion/hyperspace drive ci-dessus). Le prérequis de déblocage qu'elle imposait dans OGameX pour les unités haut de tier (Croiseur de bataille, Bombardier, Destructeur, Étoile de la mort) est **simplement retiré**, pas reporté sur une autre technologie — ces unités perdent ce palier de gating. |
| ~~Réseau de recherche intergalactique (`intergalactic_research_network`)~~ | ~~**Supprimée**~~ | Décidé le 2026-09-19 — la file de recherche reste unique à l'échelle de l'empire, pas de recherche multi-planète en parallèle |
| ~~Technologie du graviton (`graviton_technology`)~~ | ~~**Supprimée**~~ | Décidé le 2026-09-19 — pour éviter tout déséquilibre lié à une unité ultime type Étoile de la mort |
| Astrophysique (`astrophysics`) | **Découverte Tellurique** | Décidé le 2026-09-19 — recherche de nouveaux nœuds telluriques permettant de bâtir de nouveaux obélisques et de sécuriser une zone pour y implanter une colonie. Réutilise directement `MAX_COLONIES`/`MAX_EXPEDITION_SLOTS` (cf. 5.5) |
| Technologie des Armes (`weapon_technology`) | **Affûtage** *(1ᵉʳ palier d'une progression à 4, voir détail ci-dessous)* | **Décidé le 2026-09-19**, voir détail ci-dessous |
| Technologie de l'Armure (`armor_technology`) | **Forge des Armures** | Registre physique/martial, robustesse des soldats |
| Technologie du Bouclier (`shielding_technology`) | **Infusion Arcanique** | Crée une barrière protectrice magique autour des unités — équivalent du bouclier Ogame |
| Terraformeur (`terraformer`) | **[TBD — proposition : Rite d'Ancrage Tellurique]** | **Décidé le 2026-09-19** : déplacé des bâtiments vers les recherches (la catégorie "Station" n'a pas de sens narratif). Concept conservé : les érudits de la cité manipulent les lignes de mana sous l'Obélisque pour étendre son influence protectrice — l'Obélisque puise le pouvoir des Straumar *et* stabilise une zone contre les incursions de plans/failles. Techniquement, c'est un vrai changement de catégorie côté code (Station → Research), à faire consciemment |

*Nourriture : la mécanique Énergie/centrale électrique est retirée (pas de dimension steampunk voulue, cf. 4.2). La Nourriture devient une **vraie 4ᵉ ressource produite et stockée** comme les 3 autres. **Toujours TBD au 2026-09-19, mais penchant confirmé vers l'upkeep continu** plutôt que le coût ponctuel : au-delà de l'argument d'équilibrage (plafonner naturellement les armées surdimensionnées), il y a un **argument technique vérifié** — le moteur de combat (`app/GameMissions/BattleEngine/`) instancie un objet `BattleUnit` par unité individuelle (pas par pile agrégée par type), donc le coût de calcul d'une bataille scale avec le nombre brut d'unités ; c'est d'ailleurs pour ça qu'un moteur Rust dédié (`RustBattleEngine`) existe en plus du PHP, avec une commande de test de perf sur des flottes de plusieurs milliers d'unités (`ogamex:test:battle-engine-performance`). Un frein naturel à la taille des armées réduit donc réellement la charge de simulation de combat, pas seulement la pression économique. ⚠️ **Ça contredit toujours le principe "pas d'upkeep, coûts ponctuels uniquement" acté plus haut en 4.1** ("la Nourriture suit les êtres vivants, jamais la pierre") — si ce penchant se confirme en décision, il faudra explicitement révoquer/reformuler le principe de 4.1, pas le laisser cohabiter tel quel avec une vraie mécanique d'upkeep. Reste à trancher : formule de consommation par unité/tier, et si c'est un flux continu (par tick, façon Ogame Deutérium/carburant) ou un prélèvement périodique plus grossier (par exemple journalier, plus lisible pour un jeu au rythme volontairement asynchrone, cf. section 3).*

**Décidé le 2026-09-19 — la structure "4 paliers ATK façon Aleryos" devient une mécanique inédite, pas un reskin d'OGameX :**

Rappel du constat du 2026-09-18 : OGameX n'a qu'une seule technologie de bonus d'attaque (`weapon_technology`, +10 %/niveau) ; `laser_technology`/`ion_technology`/`plasma_technology` ne sont que des prérequis de déblocage sans bonus direct, et "Systèmes d'Armement" n'existe pas. Décision : on construit quand même la progression à 4 paliers d'ATK inspirée d'Aleryos, thématisée physique → magique (Affûtage → Alliage de Guerre → Runes de Combat → Bénédiction des Armes/Enchantements), **comme une vraie nouvelle chaîne de recherches sans équivalent direct dans OGameX**. Effort *élevé* (nouvelle mécanique de recherche à concevoir — formule de bonus par palier, prérequis entre paliers), assumé consciemment.

| Palier | [Nom du jeu] |
|---|---|
| 1 | **Affûtage** |
| 2 | **Alliage de Guerre** |
| 3 | **Runes de Combat** |
| 4 | **Bénédiction des Armes** *(ou "Enchantements", à trancher)* |

**[TBD]** — Formule de bonus par palier (nature exacte, % par niveau), et si `laser_technology`/`ion_technology`/`plasma_technology` d'OGameX sont réaffectées à autre chose (déblocages de bâtiments/unités thématiques) ou simplement écartées du code puisqu'elles ne servent plus de support à cette nouvelle chaîne.

### 4.4 Unités (vaisseaux → troupes)

**Catalogue complet vérifié le 2026-09-18** — 9 vaisseaux militaires (`app/GameObjects/MilitaryShipObjects.php`) + 8 civils (`app/GameObjects/CivilShipObjects.php`), 17 au total. Plusieurs unités n'étaient pas dans la version précédente du tableau (marquées **[TBD]** ci-dessous), dont 3 liées aux classes de personnage (Collector/General/Discoverer, cf. 5.5).

**Militaires :**

| Ogame/OGameX | [Nom du jeu] | Notes |
|---|---|---|
| Chasseur léger (`light_fighter`) | **Soldat** | Unité de base, peu coûteuse, nombre |
| Chasseur lourd (`heavy_fighter`) | **Garde** | Infanterie mieux protégée |
| Croiseur (`cruiser`) | **Cavalier** | Unité mobile, polyvalente |
| Vaisseau de bataille (`battle_ship`) | **Paladin** | Unité lourde d'élite |
| Croiseur de bataille (`battlecruiser`) | **[TBD]** | Absent du lexique jusqu'ici |
| Bombardier (`bomber`) | **[TBD]** | Absent du lexique — spécialisé anti-défenses statiques dans Ogame |
| Destructeur (`destroyer`) | **Rôdeur des ombres** | Spécialiste à dégâts élevés — l'ancienne ligne "Traqueur / Destructeur" est clarifiée : c'est bien `destroyer` uniquement |
| Étoile de la mort (`deathstar`, tier ultime) | **[TBD]** | *Idée gardée de côté* : un golem de guerre réactivé de l'ancienne cité déchue plutôt qu'une unité générique — pas encore décidé d'inclure une telle unité dans le jeu |
| Reaper (`reaper`) | **[TBD]** | Absent du lexique — vaisseau spécifique à la classe General (cf. 5.5), collecte 30 % des débris en plus quel que soit le propriétaire |

**Civils :**

| Ogame/OGameX | [Nom du jeu] | Notes |
|---|---|---|
| Recycleur (`recycler`) | **Récupérateur** | Collecte les ressources sur un champ de ruines après bataille |
| Petit transporteur (`small_cargo`) | **Caravane** | |
| Grand transporteur (`large_cargo`) | **Convoi** | |
| Sonde d'espionnage (`espionage_probe`) | **Éclaireur** | |
| Colonisateur (`colony_ship`) | **Pionnier** | Fonde un nouvel Éclat |
| Satellite solaire (`solar_satellite`) | **[TBD]** | Unité mineure, à retravailler ou omettre en V1 |
| Crawler (`crawler`) | **[TBD]** | Absent du lexique — vaisseau spécifique à la classe Collector (cf. 5.5), boost de production quand posé sur une planète |
| Pathfinder (`pathfinder`) | **[TBD]** | Absent du lexique — vaisseau spécifique à la classe Discoverer (cf. 5.5) |

### 4.5 Concepts de structure

| Ogame/OGameX | [Nom du jeu] | Notes |
|---|---|---|
| Position (case de carte) | **Éclat** | Déjà établi (lore section 8) |
| Planète (une fois colonisée) | **Ville** | Ce qui est bâti sur un Éclat colonisé |
| Galaxie / Système / Position | **Région / Contrée / Éclat** | Déjà établi (lore section 8) |
| Alliance | **Alliance** | Conservé tel quel — terme déjà bien compris dans le genre |
| Débris spatiaux (butin post-combat) | **Champ de ruines** | Cohérent avec le motif de vestiges/reliques du lore |

---

## 5. Systèmes détaillés

### 5.1 Production de ressources

**Base : formules Ogame/OGameX héritées telles quelles en V1**, réévaluation d'équilibrage plus tard une fois le jeu jouable. Pas de refonte mathématique dès le départ.

- ~~**[TBD]** — Nombre et nature des ressources~~ **Résolu par la section 4.1** : 4 ressources stockées (Bois/Fer/Mana/Nourriture), l'Énergie d'Ogame est retirée en tant que ressource et remplacée structurellement par la Nourriture. **Confirmé techniquement le 2026-09-18** : l'Énergie n'est pas stockée dans OGameX (solde recalculé, pas de colonne de stock) — la Nourriture stockée façon Bois/Fer/Mana est donc une vraie nouvelle mécanique à construire (pattern à copier : colonnes stock + production + max des 3 ressources classiques), pas un reskin. Effort *moyen*, déjà noté en 4.1.

### 5.2 Bâtiments et progression

- Hérite de la logique de coût croissant/temps de construction croissant d'Ogame.
- **[TBD]** — Bâtiments spécifiques ajoutés par rapport à la base Ogame (ex : bâtiment lié aux héros/gouverneurs, à la forge/craft)

### 5.3 Recherche / Arbre technologique

- **[TBD]** — Arbre hérité d'Ogame en structure, contenu à thématiser

### 5.4 Flotte / Combat

- Formules de combat héritées d'Ogame/OGameX en V1.
- Le combat PvE (contre bots) est toujours actif, indépendamment du statut du joueur.

**Mode de Guerre (PvP volontaire) :**

- **Par défaut : Mode Paix.** Invulnérabilité totale face aux autres joueurs (ressources, flotte, troupes protégées). Le PvE reste actif dans tous les cas.
- **Mode de Guerre (opt-in)** : rend le joueur attaquable par les autres joueurs également en Mode de Guerre **uniquement**. Un joueur en Mode Paix ne peut ni être ciblé, ni cibler personne — aucune situation à sens unique possible.
- **Bonus actif en contrepartie du risque accepté** — **[TBD]** nature exacte (piste : production de ressources et/ou réduction du temps de construction).
- **Délai de 72h minimum entre deux bascules** de mode (Paix→Guerre ou Guerre→Paix), pour empêcher l'activation opportuniste suivie d'une désactivation immédiate.
- **Anti-abus multicompte** : un compte en Mode Paix ne peut pas transférer de ressources vers un compte en Mode de Guerre, pour empêcher de financer un compte "exposé" via des comptes secondaires "safe".
- **Périmètre confirmé** : le Mode de Guerre est strictement cantonné au combat de flotte/armées entre joueurs. Les missions de héros restent **exclusivement PvE**, quel que soit le mode du joueur.
- **Alliances** : le mode n'a aucune incidence sur la coopération d'alliance — une alliance peut librement mélanger des membres en Mode Paix et en Mode de Guerre.
- **Échanges de ressources** : les transferts directs entre un joueur en Mode Paix et un joueur en Mode de Guerre restent bloqués (anti-abus), **sauf via un marché officiel** imposant un ratio de conversion sur ces échanges — permet un commerce encadré sans réouvrir la faille de boosting. **[TBD]** — mécanique du marché à détailler (ratio, accessible à tous ou seulement inter-modes, etc.)
  - **Existant vérifié le 2026-09-18** : `app/Services/MerchantService.php` — un marchand PNJ (pas un marché joueur-à-joueur) contre 3500 Matière Noire, taux d'échange générés aléatoirement dans des bornes fixes. Ne couvre **pas** le besoin décrit ici (un vrai ratio de conversion encadré entre deux joueurs de modes différents) — c'est un point de départ technique (génération de taux, transaction atomique) mais pas une solution prête à l'emploi. Effort *moyen*, pas *élevé*, grâce à cette base réutilisable.

### 5.5 Système Héros / Gouverneur

*Déjà cadré dans nos échanges précédents — synthèse :*

- Plusieurs héros par joueur, limite fixée à **6-7 héros maximum**
- Chaque héros peut être assigné comme **gouverneur d'une ville**, avec bonus passif thématique selon son archétype
- **Déblocage progressif** des emplacements de ville (et donc de héros) via niveau de ville principale / technologies — inspiré du modèle Aleryos (limite de colonies)
  - **Formule existante identifiée le 2026-09-18** : `astrophysics` (rebaptisée **Découverte Tellurique**, cf. 4.3, `app/GameObjects/ResearchObjects.php` id 124) calcule déjà `MAX_COLONIES = round(niveau / 2)` et `MAX_EXPEDITION_SLOTS = floor(sqrt(niveau))`. Réutilisation directe possible pour le nombre de villes/héros débloquables — effort *faible* si on adosse le déblocage à cette même recherche.
- Le bonus du héros à sa ville **reste actif même si le héros est en mission ou indisponible** (pas de pénalité d'absence)
- **Indisponibilité en cas d'échec de mission**, durée scalée par niveau du héros (progressive, jamais de perte définitive — cohérent avec le pilier accessibilité)
- **[TBD]** — Liste des archétypes de héros et leurs bonus précis
  - **Matière première existante identifiée le 2026-09-18** : `app/Services/CharacterClassService.php` (633 lignes) implémente déjà 3 profils de bonus par joueur (Collector/General/Discoverer) avec des effets chiffrés concrets — ex. Collector : ×1.25 production mines, ×2.0 vitesse transporteurs ; General : ×2.0 vitesse vaisseaux de combat, ×0.5 consommation deutérium, +2 emplacements de flotte ; Discoverer : ×0.75 temps de recherche, ×1.5 butin d'expédition, ×0.5 chance de rencontre ennemie. Ce n'est pas un système d'archétypes de héros (c'est un choix de classe *par joueur*, pas par héros individuel), mais ça donne une taxonomie de bonus déjà éprouvée et équilibrée dans laquelle piocher plutôt que d'inventer une liste de bonus dans le vide.
- **[TBD]** — Système de rançon/soin accéléré (contre ressources, cohérent avec le lore)

**Progression XP/niveau et arbre de talents — cadré le 2026-09-15 :**

- **Courbe d'XP par niveau — validée le 2026-09-17** : `xp_requise(niveau) = 100 × niveau^exposant(niveau)`, avec un exposant **interpolé linéairement de 1.2 (niveau 1) à 1.4 (niveau 30)** plutôt qu'une valeur fixe (implémentée dans `app/Services/HeroLevelingService.php`). Palier 1→2 = 100 XP, palier 29→30 = 10 896 XP, **XP cumulée totale sur toute la carrière : 118 920**.
  - Note technique : ce n'est volontairement **pas** une courbe logarithmique sur le seuil d'XP lui-même (ça l'aplatirait et accélérerait les montées de niveau en fin de course, l'inverse de l'effet recherché — l'intuition visée était une **exponentielle**). C'est une loi de puissance sur le seuil absolu qui produit l'effet "rapide au début, de plus en plus long ensuite".
  - Alternative testée et écartée : un exposant fixé **par palier de niveaux** (1.2 sur 1-10, 1.3 sur 11-20, 1.4 sur 21-30) — écartée car elle crée un saut brutal (+42 à +44 %, contre +7 à +13 % normalement) à chaque frontière de palier (10/11 et 20/21). L'interpolation continue retenue élimine ce défaut tout en gardant la même intention.
  - `BASE_XP = 100`, `EXPONENT_START = 1.2`, `EXPONENT_END = 1.4` restent des constantes de tuning, pas un équilibrage final (Phase 6).
- **Niveau maximum du héros : 30** — décidé le 2026-09-16.
- **Points de talent** : 1 point gagné à chaque montée de niveau (façon World of Warcraft), soit `niveau - 1` points cumulés au total pour un héros qui n'a jamais respec — **29 points** au niveau maximum 30. C'est la contrainte qui dimensionne la taille de l'arbre de talents (le coût total pour tout débloquer doit dépasser 29 points).
- **Arbre de talents par archétype** : chaque archétype de héros a son propre arbre de talents fixe. Principe de profondeur de gameplay : le nombre total de points qu'un héros peut gagner sur sa carrière (29, cf. ci-dessus) doit rester **inférieur** au coût total pour débloquer l'intégralité d'un arbre — le joueur est donc structurellement forcé de faire des choix de spécialisation plutôt que de finir par tout prendre.
  - **Squelette technique posé** (`talent_nodes` = catalogue de talents par archétype/branche avec tier/coût/rang max/prérequis optionnel, `hero_talents` = allocation des points par héros) — mockup graphique construit le 2026-09-18 : 3 branches de démo sur l'archétype "Guerrier" (Voie de la Lame / Voie du Bastion / Voie du Serment, 5 tiers chacune) + 1 talent capstone ("Colère du Gouffre"), 16 nœuds au total, coût croissant par tier (1 → 3 points) pour qu'un budget de 29 points ne permette pas de tout prendre. **Contenu toujours à définir** (c'est du placeholder pour la visualisation, pas un équilibrage).
  - **Disposition de l'arbre : verticale par paliers, façon World of Warcraft** — décidé le 2026-09-17, implémenté le 2026-09-18 (colonnes = branches, lignes = tiers, connecteurs visuels entre tiers investis), plutôt qu'un sphérier façon Path of Exile jugé disproportionné pour un budget de 29 points par héros.
  - **[TBD]** — nombre de branches par archétype (3 pour l'instant, à confirmer), schéma exact des tiers, et bien sûr la liste complète des archétypes et de leurs vrais talents.
- **Fiche héros façon Diablo** — cadrée le 2026-09-16, construite le 2026-09-18 sur `/heroes/{id}` :
  - **Panneau de statistiques** : 4 caractéristiques primaires (Force, Intelligence, Volonté, Dextérité) + 3 statistiques dérivées affichées en avant (Puissance d'attaque, Armure, Vie). Squelette posé (colonnes sur `heroes`), valeurs de démo saisies à plat pour l'instant — **[TBD]** la formule de calcul des stats dérivées à partir des primaires, et la courbe de croissance par niveau/archétype (Phase 6, équilibrage).
  - **Inventaire en "paper doll"** façon Diablo : 8 emplacements équipables disposés en silhouette (Tête en haut ; Arme main gauche / Torse / Arme main droite au centre ; Mains / Ceinture ; Jambes ; Bottes en bas), + grille d'inventaire pour les objets possédés non équipés. Objets colorés/surlignés selon leur rareté (5 paliers déjà posés en 5.7). Les sockets/runes visibles sur les captures de référence (Diablo IV) ne sont **pas** repris — hors périmètre de cette passe, à revisiter si le craft/forge (5.7) en a besoin plus tard.
  - **Direction artistique retenue (2026-09-18)** : ambiance **arcane mystique sombre**, cohérente avec le lore (Straumar, mana, ruines anciennes) — fond quasi noir, accents bleu/violet lumineux façon runes, halo lumineux sur les éléments actifs (talents investis, objets équipés). Typographie des titres en **Cinzel** (Google Fonts). Sert de premier repère visuel avant le reskin complet (Phase 3), pas un design final.
- **Avatar de héros** : emplacement réservé sur la carte et la fiche héros (colonne `avatar`, nullable) — aucun pipeline d'assets graphiques pour l'instant, juste l'espace UI.
- **Équipement de démo** : 8 objets (un par emplacement) avec noms rattachés au lore (ex. "Tranche-Brume, Lame des Vahrun" en légendaire, "Cuirasse des Ruines Elfiques" en épique) — contenu placeholder pour la visualisation, pas un catalogue réel.
- **Fiche héros / inventaire** : cliquer sur une carte héros ouvre sa fiche (`/heroes/{id}`), qui affiche le panneau de stats, l'arbre de talents et l'inventaire d'équipement (voir section 5.7).
- **Héros de démo "John Doe"** : créé via `php artisan ogamex:dev:seed-demo-hero` (recrée le héros, l'arbre de talents complet et les 8 objets de démo à chaque exécution), sert de référence visuelle pour la fiche héros / carte d'aperçu. Archétype **"Guerrier"** utilisé comme simple placeholder tant que la liste d'archétypes n'est pas tranchée.

### 5.6 Prestige d'Empire

*Déjà cadré — synthèse :*

- **Une seule jauge par joueur** (pas une jauge par héros), alimentée par les résultats (succès/échec) des missions de tous les héros confondus
- Effet sur la production : paliers (malus / neutre / bonus), jamais linéaire pur, avec un **plancher garanti** pour éviter la spirale négative
- Gain/perte **pondéré par la difficulté de la mission** (pas de farming de missions triviales pour gonfler le prestige)
- **Régénération passive lente** dans le temps
- Contribution (au moins partielle) à un **score de prestige d'alliance**
- **[TBD]** — Paliers précis (seuils, pourcentages de bonus/malus)
- **[TBD]** — Débouchés positifs du prestige élevé (titres, missions exclusives, bonus de recrutement)

### 5.7 Craft / Forge

*Déjà cadré — synthèse :*

- Système **hybride** : ressources + plans/recettes débloqués **et** composants rares lootés en PvE
- **Équipement de héros par slots — décidé le 2026-09-18** : Tête, Torse, Mains, Ceinture, Jambes, Bottes, + 2 emplacements d'arme (main gauche / main droite). 8 emplacements au total.
- **[TBD]** — Table de recettes, source précise des composants rares (quels donjons/missions les droppent)

**Tiers de rareté — cadré le 2026-09-15 :**

- Échelle à 5 paliers : **Blanc → Vert → Bleu → Violet → Orange** (implémentée dans `app/Enums/EquipmentRarity.php`, avec couleur associée pour l'UI).
- Squelette technique posé (`equipment_items` = catalogue d'objets, `hero_equipment` = objets possédés par un héros, équipés ou en inventaire) — **contenu (recettes, stats par objet, sources de drop) non défini**, seulement 3 objets de démo pour la fiche héros.
- Inventaire du héros pensé façon **Diablo** : emplacements équipés (arme/armure/accessoire) + grille d'inventaire, objets colorés selon leur rareté. Accessible depuis la fiche héros (clic sur la carte héros).

### 5.8 Bots PvE

**Deux familles de contenu, combinées en V1 :**

**A. Cibles fixes — camps hostiles dans la grille**
- Camps d'orcs/gobelins et vestiges gardés (les "gardiens du Gouffre" du lore) générés sur les Éclats non colonisés, garnison NPC scriptée (pas d'IA réelle)
- Danger croissant selon la Contrée — cohérent avec le gradient centre/périphérie posé en section 8 du lore
- **Scoutables via espionnage** avant d'engager (garnison visible) : engagement stratégique et prévisible, risque connu à l'avance
- Régénération périodique une fois vidées/pillées
- Vecteur principal des missions de héros et de la jauge de prestige (le joueur assume un risque calculé, pas subi)

**B. Missions à risque — expéditions**
- Envoi de flotte/héros vers l'inconnu (au-delà de sa Région, ou vers des Straumar instables), résultat probabiliste — façon Expédition Ogame (déjà présent dans OGameX, réutilisation technique probable à confirmer en Phase 2)
- Issues possibles : ressources, recrues/population, rencontre hostile (pertes), composants rares de craft, échec neutre
- Durée et risque variables selon l'ambition de l'expédition choisie
- Complémentaire aux cibles fixes : plus de variance, meilleur vecteur pour le loot rare

**[TBD]** — Nature exacte du butin par palier de risque, fréquence/limite quotidienne d'expéditions, paliers de difficulté précis (faible/moyen/fort/légendaire)

### 5.9 Alliance / Social

**Deux familles d'events d'alliance, selon le type d'occasion :**

**Type "Boss à seuils"** — objectif commun ponctuel (ex : manifestation de corruption majeure, écho du Gouffre) où chaque membre contribue à une jauge partagée dans une fenêtre de temps limitée. Récompense collective au seuil atteint, part individuelle proportionnelle à la contribution. Réservé aux moments forts et rares (rythme à définir — mensuel ? lié à un jalon narratif ?).

**Type "Score cumulé"** — objectif alimenté par l'addition des scores individuels de chaque membre sur une période (ex : chasse aux hordes hebdomadaire), sans coordination stricte requise entre joueurs. Classement entre alliances, récompenses par palier de score total atteint. Format plus léger et régulier, cohérent avec le pilier accessibilité (aucune pression de coordination d'horaires).

**[TBD]** — Fréquence exacte de chaque type d'event, nature précise des récompenses, lien fin avec le prestige d'empire (un event d'alliance réussi contribue-t-il au prestige individuel de chaque participant, ou seulement au score d'alliance ?)

**Vérifié le 2026-09-18 — aucun des deux types n'a d'équivalent dans OGameX, effort *élevé* confirmé pour les deux :**
- **ACS** (`app/GameMissions/AcsDefendMission.php`) : un seul type existe, l'ACS Défense (regroupement de flottes alliées qui "tiennent" une position ensemble). Pas d'ACS Attaque, pas de contribution à un objectif partagé.
- **Dépôt d'alliance** (`app/Services/AllianceDepotService.php`) : ne gère aucune contribution collective — sert uniquement à payer du deutérium pour prolonger la durée de tenue d'une flotte ACS Défense. Aucun rapport avec un système "boss à seuils".
- **`AllianceHighscore`** (`app/Models/AllianceHighscore.php`) : un simple classement, somme automatique des scores individuels des membres (general/economy/research/military). C'est un leaderboard, pas un système à seuils/récompenses déblocables — mais c'est la même mécanique de somme que le type "Score cumulé" recherche, donc un bon point de départ technique pour ce type précis (pas pour "Boss à seuils", qui reste 100 % neuf).

### 5.10 Tableau de correspondance technique GDD ↔ OGameX (référence)

**Construit le 2026-09-18, audit complet.** Décision actée : on continue sur la base OGameX (aucun blocage factuel trouvé, cf. échange du 2026-09-18). Ce tableau récapitule, système par système, ce qui existe dans OGameX et l'effort d'adaptation estimé — c'est la référence à tenir à jour à mesure que le développement avance.

| Système GDD | Existe dans OGameX ? | Effort | Fichiers clés |
|---|---|---|---|
| Production de ressources (5.1) | Oui, hérité tel quel | **Faible** (Nourriture stockée : **moyen**, vraie nouvelle mécanique) | `app/GameObjects/BuildingObjects.php`, `app/Services/PlanetService.php` |
| Bâtiments et progression (5.2) | Oui, catalogue complet (18 objets Building+Station) | **Faible à moyen** — lexique bouclé le 2026-09-19 (2 suppressions, 7 renommés dont l'Hôpital qui embarque une nouvelle mécanique de combat en plus du reskin ; seule l'usine de nanites reste en attente de nom) | `app/GameObjects/BuildingObjects.php`, `StationObjects.php`, `app/GameMissions/BattleEngine/` (pour l'Hôpital) |
| Recherche / arbre techno (5.3) | Oui, 16 technologies, la plupart réutilisées telles quelles | **Faible** globalement — sauf la chaîne ATK à 4 paliers, **décidée le 2026-09-19 comme mécanique inédite** (effort *élevé*, sans équivalent OGameX) | `app/GameObjects/ResearchObjects.php` |
| Flotte / Combat PvE (5.4, base) | Oui, moteur PHP + Rust FFI | **Faible**, hérité tel quel | `app/GameMissions/BattleEngine/`, `rust/` |
| Mode de Guerre — bascule PvP (5.4) | Non, rien d'équivalent | **Élevé** — point d'accroche identifié : champ sur `users` + middleware + check dans `AttackMission::isMissionPossible()` | `app/Models/User.php`, `app/GameMissions/AttackMission.php` |
| Marché inter-modes (5.4) | Partiel — marchand PNJ existant, pas un marché joueur-à-joueur | **Moyen** (base technique réutilisable : génération de taux, transaction atomique) | `app/Services/MerchantService.php` |
| Héros / Gouverneur (5.5) | Non à la base — **construit cette session** | **Élevé, déjà bien avancé** — zéro friction de framework rencontrée | `app/Models/Hero.php`, `TalentNode.php`, `app/Http/Controllers/HeroController.php` |
| Déblocage villes/héros (5.5) | Oui — `astrophysics` calcule déjà `MAX_COLONIES`/`MAX_EXPEDITION_SLOTS` | **Faible** si réutilisé directement | `app/GameObjects/ResearchObjects.php` (id 124) |
| Archétypes de héros — bonus (5.5) | Pas d'équivalent direct, mais taxonomie de bonus réutilisable (`CharacterClassService`) | **Moyen** — matière première existante, contenu à écrire | `app/Services/CharacterClassService.php` |
| Mission héros — durée (section 3) | Oui, formule de trajet de flotte réutilisable | **Faible** | `app/Services/FleetMissionService.php` (`calculateFleetMissionDuration`) |
| Prestige d'Empire (5.6) | Amorce seulement (`Highscore`, leaderboard) | **Moyen** — la jauge/les paliers restent 100 % à construire | `app/Models/Highscore.php` |
| Craft / Forge (5.7) | Non à la base — **squelette construit cette session** | **Élevé, entamé** — contenu (recettes, sources de drop) non défini | `app/Models/EquipmentItem.php`, `HeroEquipment.php`, `app/Enums/EquipmentRarity.php` |
| Bots PvE — cibles fixes (5.8A) | Infra existante, utilisée seulement en expédition | **Moyen** — à étendre hors du contexte expédition | `app/Services/NPCPlayerService.php`, `NPCPlanetService.php`, `NPCFleetGeneratorService.php` |
| Bots PvE — expéditions (5.8B) | Oui, très développé et quasi prêt à l'emploi | **Faible** | `app/GameMissions/ExpeditionMission.php` |
| Events d'alliance (5.9) | Non — ACS/Dépôt/Highscore ne couvrent pas le besoin | **Élevé** pour "Boss à seuils" ; **moyen** pour "Score cumulé" (mécanique de somme déjà là) | `app/GameMissions/AcsDefendMission.php`, `app/Models/AllianceHighscore.php` |
| Quêtes / PNJ narratifs (section 3) | Non, aucun système | **Élevé**, 100 % neuf | — |
| Alliances, chat, admin, i18n, auth | Oui, complets et fonctionnels | **Nul** — réutilisable directement | `app/Http/Controllers/AllianceController.php`, `ChatController.php`, `Admin/` |

---

## 6. Contenu (catalogues)

**[TBD — à remplir au fur et à mesure, une fois le lexique de conversion (section 4) stabilisé]**

- 6.1 Catalogue de bâtiments
- 6.2 Catalogue d'unités/troupes
- 6.3 Catalogue de recherches
- 6.4 Catalogue d'équipements de héros (par rareté)
- 6.5 Bestiaire des bots PvE (par palier de difficulté)

---

## 7. Progression et courbe de difficulté

**[TBD]**

- Rythme d'accès aux nouvelles villes/héros
- Paliers de difficulté du PvE dans le temps
- Sentiment de progression early/mid/end-game

---

## 8. Univers / Lore

**Correction 2026-09-18** : cette section était marquée "pas encore abordé", ce qui n'est plus exact — l'univers est développé séparément dans **[docs/lore.md](lore.md)** (cosmologie des Straumar/Neuf Sphères, chronologie de la Rupture, les Vahrun, races jouables, structure Région/Contrée/Éclat), déjà référencé abondamment dans ce document (sections 4.2, 4.5, 5.8, etc.). Voir ce fichier plutôt que cette section.

- Ambiance retenue pour l'UI (arcane mystique sombre, cf. 5.5) cohérente avec ce lore — à confirmer que c'est aussi la direction voulue pour l'univers narratif dans son ensemble, pas seulement l'interface héros.
- **[TBD]** — Nom du monde encore en discussion (cf. lore.md section 9)

---

## 9. Monétisation (rappel — pas prioritaire actuellement)

*Pour mémoire, à ne creuser qu'en Phase 8 de la roadmap :*

- Cosmétique, gain de temps, rançon de héros — jamais de pay-to-win direct
- **[TBD]** — détails à définir le moment venu

---

## Notes de méthode

- Les formules mathématiques précises (coûts, temps, dégâts) restent héritées d'Ogame/OGameX en V1 pour ne pas bloquer le développement sur de l'équilibrage prématuré. Une passe d'ajustement est prévue une fois le jeu jouable et testé (Phase 6 de la roadmap).
- Ce document est destiné à vivre dans `docs/game-design.md` une fois le repo Git en place, lu automatiquement par Claude Code via `CLAUDE.md`.

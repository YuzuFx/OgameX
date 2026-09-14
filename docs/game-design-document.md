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
- Compléter une quête hebdomadaire narrée par un PNJ
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

**[TBD]** — Point à affiner : la mécanique précise du calcul de durée d'une mission de héros (distance à la cible dans la grille Région/Contrée/Éclat, difficulté du bot ciblé, vitesse propre du héros/de sa garnison) — probablement une adaptation directe de la formule de trajet de flotte d'Ogame, à valider une fois l'audit technique (Phase 2) réalisé.

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

| Ogame/OGameX | [Nom du jeu] | Notes |
|---|---|---|
| Mine de métal | **Scierie** | Production de bois (Métal → Bois) |
| Mine de cristal | **Mine de fer** | Production de fer (Cristal → Fer) |
| Synthétiseur de deutérium | **Obélisque de mana** | Cf. lore section 1 |
| *(nouveau, remplace la Centrale électrique)* | **Champ de blé** | Production de nourriture — nom repéré chez Embercraft |
| Entrepôt de métal | **Entrepôt de bois** | |
| Entrepôt de cristal | **Entrepôt de fer** | |
| Entrepôt de deutérium | **Réservoir de mana** | |
| *(nouveau)* | **Silo de nourriture** | |
| Chantier spatial | **Forge de guerre** | Construit toutes les unités déployables |
| Laboratoire de recherche | **Académie** | |
| Base lunaire | *(retiré)* | Aucune pertinence dans notre cosmologie, également absent d'Aleryos |
| Robot factory / Nanite factory | **Atelier des Maîtres d'œuvre** | Nom repéré chez Embercraft |

*Terraformeur déplacé en section 4.3 (Recherches) — voir Expansion tellurique.*

### 4.3 Recherches / Technologies

| Ogame/OGameX | [Nom du jeu] | Notes |
|---|---|---|
| Terraformeur | **Expansion tellurique** | Reclassé en recherche plutôt qu'en bâtiment — cohérent avec la dimension arcane de l'extension de capacité d'un Éclat, plutôt qu'une construction physique |
| Technologie énergétique | **Abondance** *(proposition)* | Repositionnée comme techno de production de Nourriture — symétrique aux techs de production des trois autres ressources si elles existent dans OGameX (à confirmer en Phase 2) |
| Technologie de propulsion | **Célérité** | Vitesse de déplacement des troupes |
| Espionnage | **Espionnage** | Conservé tel quel, fonctionne déjà bien |
| Technologie des Armes — 4 paliers (ATK) | *voir détail ci-dessous* | Structure calquée sur Aleryos (Laser/Ionique/Plasma/Systèmes d'Armement, bonus distincts par palier) |
| Technologie de l'Armure (COQ / robustesse) | **Forge des Armures** | Registre physique/martial, robustesse des soldats |
| Technologie du Bouclier (BOU) | **Infusion Arcanique** | Crée une barrière protectrice magique autour des unités — équivalent du bouclier Ogame |

*Nourriture confirmée sans mécanique de consommation (upkeep) — se comporte comme les trois autres ressources, cohérent avec le pilier d'accessibilité (cf. section 2).*

**Détail — Technologie des Armes, 4 paliers (progression physique → magique) :**

| Ogame/Aleryos | [Nom du jeu] |
|---|---|
| Technologie Laser | **Affûtage** |
| Technologie Ionique | **Alliage de Guerre** |
| Technologie Plasma | **Runes de Combat** |
| Systèmes d'Armement | **Bénédiction des Armes** |

**[TBD]** — Confirmation des bonus distincts par palier (nature exacte à définir).

### 4.4 Unités (vaisseaux → troupes)

| Ogame/OGameX | [Nom du jeu] | Notes |
|---|---|---|
| Chasseur léger | **Soldat** | Unité de base, peu coûteuse, nombre |
| Chasseur lourd | **Garde** | Infanterie mieux protégée |
| Croiseur | **Cavalier** | Unité mobile, polyvalente |
| Vaisseau de bataille | **Paladin** | Unité lourde d'élite |
| Traqueur / Destructeur (tiers sup.) | **Rôdeur des ombres** | Spécialiste à dégâts élevés |
| Étoile de la mort (tier ultime) | **[TBD]** | *Idée gardée de côté* : un golem de guerre réactivé de l'ancienne cité déchue plutôt qu'une unité générique — pas encore décidé d'inclure une telle unité dans le jeu |
| Recycleur | **Récupérateur** | Collecte les ressources sur un champ de ruines après bataille |
| Petit transporteur | **Caravane** | |
| Grand transporteur | **Convoi** | |
| Sonde d'espionnage | **Éclaireur** | |
| Colonisateur | **Pionnier** | Fonde un nouvel Éclat |
| Satellite solaire | **[TBD]** | Unité mineure, à retravailler ou omettre en V1 |

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

- **[TBD]** — Nombre et nature des ressources (garder les 3 ressources + énergie d'Ogame, ou ajuster ?)

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

### 5.5 Système Héros / Gouverneur

*Déjà cadré dans nos échanges précédents — synthèse :*

- Plusieurs héros par joueur, limite fixée à **6-7 héros maximum**
- Chaque héros peut être assigné comme **gouverneur d'une ville**, avec bonus passif thématique selon son archétype
- **Déblocage progressif** des emplacements de ville (et donc de héros) via niveau de ville principale / technologies — inspiré du modèle Aleryos (limite de colonies)
- Le bonus du héros à sa ville **reste actif même si le héros est en mission ou indisponible** (pas de pénalité d'absence)
- **Indisponibilité en cas d'échec de mission**, durée scalée par niveau du héros (progressive, jamais de perte définitive — cohérent avec le pilier accessibilité)
- **[TBD]** — Liste des archétypes de héros et leurs bonus précis
- **[TBD]** — Système de rançon/soin accéléré (contre ressources, cohérent avec le lore)

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
- Équipement de héros par slots (arme/armure/accessoires, à préciser)
- **[TBD]** — Tiers de rareté, table de recettes, source précise des composants rares (quels donjons/missions les droppent)

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

**[TBD — pas encore abordé]**

- Nom du monde, ambiance générale (dark fantasy / high fantasy / autre)
- Factions/races si applicable
- Trame narrative de fond (pourquoi le joueur est souverain, contexte du conflit PvE)

---

## 9. Monétisation (rappel — pas prioritaire actuellement)

*Pour mémoire, à ne creuser qu'en Phase 8 de la roadmap :*

- Cosmétique, gain de temps, rançon de héros — jamais de pay-to-win direct
- **[TBD]** — détails à définir le moment venu

---

## Notes de méthode

- Les formules mathématiques précises (coûts, temps, dégâts) restent héritées d'Ogame/OGameX en V1 pour ne pas bloquer le développement sur de l'équilibrage prématuré. Une passe d'ajustement est prévue une fois le jeu jouable et testé (Phase 6 de la roadmap).
- Ce document est destiné à vivre dans `docs/game-design.md` une fois le repo Git en place, lu automatiquement par Claude Code via `CLAUDE.md`.

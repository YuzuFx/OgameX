# Décision d'architecture — Refonte de l'interface sur moteur OGameX conservé

**Statut :** proposé — à valider par Gregory
**Date :** 2026-09-26
**Portée :** redéfinit la Phase 3 de la [feuille de route](feuille-de-route-projet-jeu.md), qui parlait de « reskin » et devient une refonte d'interface cadrée.

---

## 1. Contexte

La Phase 2 a validé le moteur OGameX (PHPStan niveau 8 sans erreur sur 705 fichiers, 1109 tests passants, architecture en couche de services propre) et reporté volontairement le seul point dur : la couche visuelle legacy.

Deux constats ont depuis fait évoluer le cadrage :

1. **La vision de jeu s'éloigne du gabarit Ogame.** Le GDD (héros/gouverneur, prestige d'empire, PvE contre bots, roue à huit secteurs) ne se laisse pas habiller dans une coquille figée à 990 px.
2. **Les deux jeux de référence de Gregory ont été audités** (2026-09-24) et sont tous deux écrits de zéro, par une personne seule assistée par IA :
   - **Aleryos** — SPA React (Vite/Rolldown), API Fastify derrière Caddy, auth Supabase, WebSockets, app Android.
   - **Embercraft** — SPA React (Vite) sur Vercel, **aucun backend applicatif** : 104 procédures stockées PostgreSQL appelées directement depuis le client via Supabase.

La portée visée est donc démontrée atteignable en solo. La question n'est pas « en est-on capable » mais « que faut-il garder ».

## 2. Décision

**Conserver intégralement le domaine PHP/Laravel. Jeter intégralement la couche de présentation. Exposer le domaine par une API JSON et reconstruire l'interface en React, écran par écran.**

Le raisonnement tient à un rapport de volumes mesuré sur le repo :

| Couche | Volume | Sort |
|---|---:|---|
| CSS legacy (`public/css`, `resources/css`) | 113 609 lignes | **jeté** |
| Vues Blade (`resources/views`) | 25 045 lignes | **jeté** |
| Services métier (`app/Services`) | 16 895 lignes | conservé |
| Contrôleurs HTTP (`app/Http/Controllers`) | 12 414 lignes | réécrits en contrôleurs API fins |
| Missions + moteur de combat (`app/GameMissions`) | 8 265 lignes | conservé |
| Modèles + `GameObjects` | 7 072 lignes | conservé |
| **Tests (`tests/`)** | **40 779 lignes** | **conservé — filet de sécurité de la refonte** |

Sur ~175 000 lignes qui posent problème, **138 000 sont de la présentation**. Le moteur n'est pas le problème : il est l'actif.

### Ce que le moteur contient déjà et qu'il serait absurde de réécrire

Vérifié dans le code, pas supposé :

- **Champ d'épaves** (`WreckFieldService`) avec la table de multiplicateurs du Chantier de réparation d'origine (0,45 → 0,55+ selon le niveau), appliquée à la part non-débris — soit le modèle « détruit / récupérable / réparable » observé sur Aleryos.
- **Réparation des défenses** (`DefenseRepairService`), 70 % de chance par unité, avec seed déterministe pour les tests.
- **Moteur de combat sans aucun couplage à Eloquent** : `BattleUnit`, `BattleResultRound`, `TacticalRetreatService` sont des objets métier purs — donc portables et testables isolément. Double implémentation PHP et Rust (`RustBattleEngine`) pour les grandes flottes.
- **Base PvE déjà présente en amont** : `NPCPlayerService`, `NPCFleetGeneratorService`, `NPCPlanetService` (combats d'expédition contre pirates et aliens, upstream depuis décembre 2025).
- 10 types de missions de flotte, files de construction/recherche/unités, galaxie, espionnage, phalange, porte de saut, alliances avec dépôt.

**Nuance importante, à ne pas confondre :** le GDD a décidé le 2026-09-19 (section 5.2, `space_dock` → **Hôpital**) de *remplacer* la fonction OGameX de ce bâtiment — réparation de vaisseaux et ratio épaves/débris — par une mécanique inédite de troupes blessées récupérées après combat. Le code existant n'est donc pas à conserver tel quel ici : `WreckFieldService` et `DefenseRepairService` sont le **point de départ** de cette mécanique (modèle à trois états déjà en place, testé, avec seed déterministe), pas sa version finale. Cela ne change rien à la décision d'architecture : c'est un chantier de **Phase 4**, sur le domaine, indépendant de la refonte d'interface.

## 3. Stack retenue

Le repo est déjà sur **PHP 8.5, Laravel 13, Vite 8, avec Laravel Reverb installé** et `BROADCAST_CONNECTION=reverb`. Le socle moderne est en place ; l'essentiel des ajouts concerne le front et la performance.

| Couche | Choix | Justification |
|---|---|---|
| Domaine | PHP 8.5 / Laravel 13 *(conservé)* | 30 000 lignes de moteur, 40 779 lignes de tests, upstream activement maintenu. |
| Base de données | MySQL 8 *(conservé)* | Pas le goulot d'étranglement. Migrer vers PostgreSQL = risque sans gain, et casserait l'alignement avec l'upstream. |
| Runtime | **FrankenPHP + Laravel Octane** (mode worker) | Application bootée en mémoire : démarrage par requête de ~50-80 ms ramené à ~2-5 ms. Meilleur rapport gain/effort du lot. |
| Cache / sessions / file | **Redis** (remplace `database`) + **Horizon** | Prérequis de la résolution planifiée (§5). |
| Temps réel | **Reverb + Laravel Echo** *(déjà installés)* | Serveur WebSocket first-party, aucun service Node à maintenir — avantage net sur l'architecture d'Aleryos. |
| API | **JSON versionnée `/api/v1` + Sanctum** | Découplage total : une app mobile devient un client de plus, pas une réécriture. |
| Front | **React 19 + TypeScript + Vite 8** | Choix des deux références, écosystème le plus profond pour de l'UI de jeu. |
| Routage / données | **TanStack Router + TanStack Query** | Query apporte cache, invalidation et *optimistic updates* — cœur de la réactivité perçue. |
| UI / design | **Tailwind 4 + shadcn/ui (Radix)** | Composants copiés dans le repo, pas une librairie subie : permet de suivre n'importe quel mockup sans lutter contre un thème. Accessibilité fournie par Radix. |
| État local | **Zustand** | Léger, suffisant — l'état serveur appartient à Query. |
| Animation | **Motion** | Transitions d'écran, compteurs, feedback de combat. |
| i18n | clés existantes de `resources/lang/` exposées par l'API, consommées via **i18next** | Le contenu reste conçu en français d'abord (cf. CLAUDE.md) ; l'infrastructure de traduction est préservée. |
| Qualité | **Pest** *(conservé)* + **Playwright** + PHPStan/Larastan *(conservé)* | Le domaine reste couvert ; les parcours d'interface deviennent couverts. |

### Alternatives écartées

- **Inertia.js** — plus rapide à écrire, mais colle le front aux routes Laravel et rend une app mobile future coûteuse. Écarté au nom de l'exigence « ne pas se limiter plus tard ».
- **Réécriture complète en Node/TypeScript (modèle Aleryos)** — ferait perdre les 40 779 lignes de tests, qui encodent des centaines de cas limites d'Ogame (arrondis de production, ordre de résolution des rounds, retours de flotte). Aucun besoin du projet n'exige un changement de langage.
- **Logique de jeu en SQL (modèle Embercraft)** — imbattable pour démarrer seul, mais rend l'équilibrage de Phase 6 douloureux et interdit une suite de tests comparable.
- **Élargir le gabarit CSS existant** — évalué le 2026-09-18 : 1 150+ règles `position: absolute` sur une base minifiée/obfusquée. Non viable, et c'est le point de départ de cette décision.

## 4. Découpage domaine / API

Règle : **les contrôleurs API ne contiennent aucune logique de jeu.** Ils valident l'entrée, appellent un service existant, sérialisent la sortie. Toute règle métier qui manquerait s'ajoute dans `app/Services` ou `app/GameMissions`, avec son test Pest.

Conséquence pratique : les tests existants restent valides sans modification, puisqu'ils exercent les services et non les contrôleurs.

Trois familles de points d'entrée :

1. **Lecture d'état** — `GET /api/v1/planet/{id}/overview`, `/buildings`, `/research`, `/shipyard`… Renvoient l'état **plus l'heure serveur** et des **horodatages absolus** (jamais de durées restantes, cf. §5).
2. **Actions** — `POST /api/v1/queue/building`, `/fleet/dispatch`, `/fleet/recall`… Idempotentes autant que possible, réponse contenant l'état résultant pour permettre la réconciliation optimiste.
3. **Diffusion** — canaux Reverb privés par joueur (`player.{id}`) et par domaine (`planet.{id}`) : arrivée de flotte, fin de file, rapport de combat, message reçu.

## 5. Chantier transversal : la résolution temporelle

**C'est le seul travail de fond sur le domaine, et il conditionne la qualité perçue du jeu.**

État actuel vérifié : OGameX résout les missions de flotte **paresseusement, dans un middleware global** (`app/Http/Middleware/GlobalGame.php`), à chaque requête HTTP. `app/Jobs/` ne contient qu'un seul job. L'état du jeu n'avance donc que lorsque quelqu'un sollicite le serveur. C'est aussi l'origine probable des latences de 1 à 2 secondes observées sur Aleryos à l'expiration d'un compteur de flotte : changer de langage n'y changerait rien, c'est architectural.

Les quatre règles cibles :

1. **Le serveur ne renvoie jamais de durée, uniquement des horodatages absolus** (`arrival_at`, `completed_at`) accompagnés de son heure courante. Le client mesure une fois son décalage d'horloge et le corrige.
2. **Le client interpole localement.** Le compte à rebours tourne dans le navigateur, sans appel réseau, et atteint zéro à la seconde exacte.
3. **La résolution devient planifiée.** Un job Redis différé s'exécute à l'horodatage prévu ; l'état avance même sans joueur connecté. Le middleware paresseux est conservé un temps comme filet de rattrapage, puis retiré.
4. **Reverb pousse le résultat** au moment de la résolution : le client affichait déjà zéro et remplace le compteur par le rapport réel. Combiné aux *optimistic updates*, une action joueur s'affiche instantanément puis se réconcilie.

Risque à surveiller : une mission ne doit jamais être résolue deux fois (job planifié **et** rattrapage paresseux). Verrou applicatif sur la mission + idempotence de `updateMission()`, couverts par des tests dédiés avant bascule.

## 6. Ordre de bascule

Cohabitation assumée pendant toute la durée : les écrans non encore refaits continuent de tourner en Blade. L'ancien et le nouveau coexistent derrière la même session.

| Lot | Contenu | Pourquoi à ce rang | Effort |
|---|---|---|---|
| **0 — Socle** | Octane/FrankenPHP, Redis + Horizon, squelette API + Sanctum, shell React (routeur, Query, Tailwind, shadcn), design system issu des mockups, Playwright | Rien ne peut commencer avant | élevé |
| **1 — Pilote : Héros** | Écran héros/talents/équipement | Code **déjà écrit par nous** (2026-09-19), aucune dette legacy, périmètre maîtrisé : valide la chaîne complète sur un terrain sûr | faible |
| **2 — Boucle courte** | Aperçu, Ressources, Bâtiments/Installations (1 121 l.), Recherche, Chantier, Défense | Écrans les plus consultés, gain ressenti immédiat, formes d'UI répétitives (files d'attente) | moyen |
| **3 — Temps & espace** | Galaxie (939 l.), Flotte (1 464 l. — le plus gros écran du jeu) **+ chantier §5** | Le plus complexe ; à faire une fois le design system rodé sur le lot 2 | **élevé** |
| **4 — Rapports** | Messages, rapport de combat (775 l.), rapport d'espionnage (1 156 l.) | Fort potentiel de différenciation visuelle ; dépend du lot 3 | moyen |
| **5 — Social** | Alliance, dépôt d'alliance, chat, amis, classement | Indépendant du reste, peut glisser | moyen |
| **6 — Périphérie** | Options, marchand, prime, techtree, phalange, porte de saut, abandon/déplacement de domaine | Faible fréquence d'usage | moyen |
| **7 — Admin** | Administration serveur (703 + 500 l.) | Interne, aucun enjeu esthétique — **candidat à rester en Blade indéfiniment** | faible ou nul |

Le lot 7 mérite d'être assumé tel quel : réécrire une interface d'administration que seul Gregory utilise n'a aucune valeur.

## 7. Risques

| Risque | Mitigation |
|---|---|
| **Divergence de l'upstream** `lanedirt/OGameX` (Laravel 13, correctifs de sécurité hebdomadaires) | Ne pas toucher au domaine sauf §5. On passe de « merger l'upstream » à « cherry-pick des correctifs de domaine », ce qui reste tenable tant que `app/Services` et `app/GameMissions` restent proches de l'amont. À réévaluer si la divergence devient ingérable. |
| **Tunnel sans jeu jouable**, contraire au principe « V1 minimum jouable d'abord » | La cohabitation Blade/React garantit un jeu jouable à chaque étape. Aucun lot ne casse un écran existant avant que son remplaçant ne fonctionne. |
| Double résolution des missions au lot 3 | Verrou + idempotence + tests dédiés, avant bascule (cf. §5). |
| Régression silencieuse du domaine | Les 1109 tests tournent en continu ; aucune bascule d'écran n'est fusionnée si la suite échoue. |
| Dérive du périmètre au lot 3 | La flotte est le plus gros écran : la découper en sous-étapes (envoi, liste des mouvements, rappel, unions) plutôt qu'en une seule bascule. |

## 8. Conséquences sur la feuille de route

La Phase 3 telle qu'écrite (« renommer les entités », « remplacer les assets un par un ») décrivait un habillage de la coquille existante. Elle est remplacée par :

- **Phase 3a — Mockups et direction artistique.** Maquettes des écrans du lot 2, planche de style Midjourney (`--sref`), design system arrêté. *Prérequis de tout code front.*
- **Phase 3b — Socle technique** (lot 0) et **pilote héros** (lot 1).
- **Phase 3c — Bascule par lots** (2 à 6), le lexique fantasy du GDD étant appliqué **au fil de chaque écran refait**, jamais comme une passe de renommage séparée.

Le reskin cesse d'être une tâche distincte : chaque écran naît directement fantasy.

---

*Document vivant. Les volumes cités sont mesurés sur le repo au 2026-09-26 ; les audits d'Aleryos et Embercraft datent du 2026-09-24.*

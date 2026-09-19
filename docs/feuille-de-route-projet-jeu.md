# Feuille de route — Projet de jeu navigateur Heroic Fantasy PvE-first

*Basé sur nos échanges : clone Ogame-like réorienté fantasy, PvE-first, système héros/gouverneur, prestige d'empire, bots PvE. Développement solo via Claude Code, rythme régulier (quelques heures/semaine), zéro code manuel de ton côté.*

---

## Phase 0 — Cadrage du concept (avant tout code)

**Objectif : sortir un document de conception clair, même imparfait, pour que Claude Code sache quoi construire.**

- [ ] **Document de Game Design (GDD) minimal** — on formalise ensemble tout ce qu'on a posé jusqu'ici : univers, boucle de gameplay PvE, système héros/gouverneur, prestige d'empire, bots. Pas besoin que ce soit parfait ou complet à 100%, juste assez cadré pour démarrer.
- [ ] **Choix de l'univers narratif** — nom du monde, ambiance (dark fantasy ? high fantasy lumineuse ? à la Warhammer, à la Magic ?), les grandes factions/races si tu en veux.
- [ ] **Lister les fonctionnalités "V1 minimum jouable"** vs "plus tard" — pour ne pas se disperser. Exemple de V1 : ressources + bâtiments + recherche + flotte/troupes + combat PvE contre bots + un héros par ville. Le craft/forge avancé, le prestige d'empire fin, les events d'alliance PvE peuvent arriver en V2.
- [ ] **Trancher la base technique** : OGameX (thème spatial à reskinner) vs TravianZ (mais moins adapté vu que tu pars sur une structure "galaxie/liste" façon Ogame plutôt qu'une carte parcourable). → Ma recommandation actuelle : **OGameX**, car son architecture (liste positions/planètes) colle mieux à ce qu'on a conçu que le modèle carte de TravianZ.

**Durée estimée : 1 à 3 sessions de travail avec moi, pas de code impliqué.**

---

## Phase 1 — Environnement de travail

**Objectif : avoir un poste de travail prêt pour que Claude Code puisse opérer.**

- [ ] **Installer VSCode** (si pas déjà fait) + l'extension Claude Code
- [ ] **Créer un compte GitHub** (gratuit) — indispensable pour versionner le code, revenir en arrière si besoin, et éventuellement plus tard travailler dessus depuis plusieurs machines
- [ ] **Forker le repo OGameX** sur ton propre GitHub (copie personnelle du projet OpenSource, sur laquelle on va travailler sans toucher à l'original)
- [ ] **Cloner le fork en local** sur ta machine de dev (pas encore le NAS à ce stade — on développe en local d'abord, plus rapide à itérer)
- [ ] **Installer Docker Desktop** en local si pas déjà présent (nécessaire pour faire tourner Laravel + MySQL proprement)

**Durée estimée : une session, principalement de l'installation guidée.**

---

## Phase 2 — Audit et prise en main de la base OGameX

**Objectif : comprendre ce qu'on a entre les mains avant de commencer à modifier, avec une méthode explicite plutôt qu'une impression générale.**

- [x] **Faire tourner OGameX en local** tel quel (sans modification), vérifier que ça fonctionne — fait le 2026-09-15, stack Docker Compose complète (db/app/scheduler/queue/webserver/reverb/phpmyadmin), compte admin Legor opérationnel.
- [x] **Explorer avec Claude Code** la structure du projet : où sont les formules de production, le système de combat, les modèles de données (planètes, bâtiments, flottes, recherche) — fait le 2026-09-15, cartographie complète (voir tableau de correspondance ci-dessous pour les chemins de fichiers clés).
- [x] **Vérifier la présence du système d'Expédition** — confirmé le 2026-09-15 : très développé (`app/GameMissions/ExpeditionMission.php`, 1138 lignes), combat PNJ dynamique, butin pondéré, couvre déjà l'essentiel du besoin PvE "missions à risque" du GDD (5.8).
- [x] **Construire un tableau de correspondance GDD ↔ OGameX** — fait le 2026-09-18 (voir le récapitulatif d'audit dans la conversation ; à formaliser dans le GDD si besoin).
- [x] **Tâche pilote sur le système Héros** — fait et largement dépassée le 2026-09-15 au 2026-09-18 : au-delà du squelette minimal prévu, le système héros a une vraie table + modèle, un arbre de talents (3 branches, 16 nœuds), un inventaire d'équipement à 8 emplacements façon Diablo, un panneau de stats, une identité visuelle (arcane mystique sombre), le tout fonctionnel sur `/heroes`. Zéro friction de framework rencontrée.
- [x] **Identifier les points d'accroche** pour les autres mécaniques différenciantes :
  - **Bots PvE** : `NPCPlayerService` / `NPCPlanetService` / `NPCFleetGeneratorService` (`app/Services/`), déjà fonctionnels, utilisés aujourd'hui uniquement dans le contexte des expéditions — point d'accroche direct pour du PvE en galaxie.
  - **Prestige d'empire** : `app/Models/Highscore.php` (colonnes general/economy/research/military + rangs) comme amorce de jauge par joueur.
  - **Mode de Guerre** : aucun équivalent existant — ajout neuf le plus probable : un champ sur `users` (statut + timestamp de bascule pour le cooldown 72h), un middleware dans le style de `banned`/`globalgame` déjà utilisés dans les groupes de routes (`routes/web.php`), et une vérification insérée dans `AttackMission::isMissionPossible()` (`app/GameMissions/AttackMission.php`) pour n'autoriser une cible que si attaquant et défenseur sont tous deux en Mode de Guerre.
- [x] **Décision finale** — validée le 2026-09-18 : **on continue sur OGameX**. Aucun blocage factuel trouvé sur le moteur de jeu (PHPStan niveau 8 sans erreur sur 705 fichiers, 1109 tests passants, architecture service-layer propre). Le seul point dur réel (CSS/gabarit visuel legacy, ~113k lignes, conteneur figé) est cosmétique, indépendant du moteur de jeu, et volontairement reporté en Phase 3.

**Phase 2 terminée (2026-09-18).**

---

## Phase 3 — Reskin thématique (spatial → heroic fantasy)

**Objectif : transformer la coquille Ogame en univers fantasy, sans encore toucher aux mécaniques avancées.**

- [ ] **Renommer les entités de base** : planètes → domaines/fiefs, flotte → armée, technologies → savoirs/arts, etc. (lexique complet à définir en Phase 0)
- [ ] **Démarrer la direction artistique** avec Midjourney : planche de style (`--sref`) pour figer palette et ambiance, premiers essais de bâtiments/unités
- [ ] **Remplacer les assets visuels de base** un par un (bâtiments, unités, fond d'écran, UI) au fur et à mesure que la DA se stabilise
- [ ] **Adapter les formules si besoin** (les formules Ogame de base peuvent rester quasi identiques en V1, seul l'habillage change)

**Durée estimée : en continu sur plusieurs semaines, en parallèle des phases suivantes — le reskin visuel n'a pas besoin d'être fini avant de coder les nouvelles mécaniques.**

---

## Phase 4 — Mécaniques différenciantes (le cœur de ton projet)

**Objectif : implémenter ce qui rend ton jeu unique. À faire dans cet ordre car chaque brique dépend en partie de la précédente.**

- [ ] **4.1 — Système de bots PvE** (le plus simple des trois, bonne première brique) : génération de cibles NPC dans la galaxie, paliers de difficulté, butin, regénération périodique
- [ ] **4.2 — Système héros/gouverneur** : entité héros (stats, niveau, XP), lien à une ville, bonus passif par archétype, statut (actif/en mission/indisponible), limite de 6-7 héros par joueur avec gating de déblocage
- [ ] **4.3 — Système de missions PvE pour héros** (attaques contre les bots de la 4.1, avec le héros engagé) — relie les deux briques précédentes
- [ ] **4.4 — Jauge de prestige d'empire** : alimentée par les résultats des missions héros (4.3), impact sur la production (bonus/neutre/malus par paliers)
- [ ] **4.5 — Système de craft/forge** : recettes hybrides (ressources+plans / composants rares de PvE), équipement de héros
- [ ] **4.6 — Indisponibilité scalée par niveau** en cas d'échec de mission, avec régénération passive de la jauge de prestige

**Durée estimée : la partie la plus longue du projet, plusieurs mois à raison de quelques heures/semaine. Chaque sous-brique peut être testée indépendamment avant de passer à la suivante.**

---

## Phase 5 — Hébergement de développement (NAS)

**Objectif : sortir du "ça tourne sur mon PC" pour tester en conditions quasi réelles avec tes potes.**

*À faire dès que la Phase 4.1-4.2 est jouable, pas besoin d'attendre la fin de la Phase 4.*

- [ ] **Vérifier le support Docker** de ton NAS (Container Manager / Container Station)
- [ ] **Déployer la stack** (Laravel + MySQL + Redis si besoin) sur le NAS via Docker
- [ ] **Mettre en place Cloudflare Tunnel** pour exposer le jeu sans ouvrir de ports sur ta box
- [ ] **Configurer une sauvegarde automatique de la base de données**, séparée du NAS
- [ ] **Inviter tes potes à tester** et itérer sur les retours

**Durée estimée : une session pour le setup initial, puis usage continu en parallèle du développement.**

---

## Phase 6 — Contenu et équilibrage

**Objectif : remplir le squelette technique avec assez de contenu pour que ce soit amusant sur la durée.**

- [ ] **Arbre technologique/recherche complet** adapté au thème fantasy
- [ ] **Catalogue de bâtiments** avec progression cohérente
- [ ] **Catalogue d'unités/troupes** avec équilibrage attaque/défense/coût
- [ ] **Catalogue d'équipements de héros** (par tier de rareté)
- [ ] **Premiers events PvE d'alliance** (si tu joues déjà avec plusieurs potes en "alliance")
- [ ] **Passes d'équilibrage** basées sur les retours de tes potes en Phase 5

**Durée estimée : en continu, jamais vraiment "fini" — c'est la phase qui vit le plus longtemps dans le temps.**

---

## Phase 7 — Préparation à l'ouverture publique (si tu vas jusque-là)

**Objectif : passer d'un jeu entre potes à quelque chose d'exposable publiquement.**

*Ne commence cette phase que quand tu es convaincu que le concept "tient" avec tes amis.*

- [ ] **Réserver un nom de domaine**
- [ ] **Choisir et souscrire un VPS** (OVH VPS-2, ~8,65€/mois, avec sauvegarde et anti-DDoS inclus — bon point de départ)
- [ ] **Migrer la stack** du NAS vers le VPS, mettre en place SSL (Let's Encrypt, gratuit une fois le domaine actif)
- [ ] **Mettre en place un pipeline de déploiement simple** (script ou CI basique pour pousser les mises à jour sans tout casser)
- [ ] **Rédiger CGU/mentions légales de base** (même minimales, nécessaires dès qu'il y a des comptes utilisateurs publics)
- [ ] **Réfléchir à la modération** (report, bannissement) même en PvE-first, ça reste nécessaire pour le chat/social

**Durée estimée : 2-4 sessions pour le setup technique, en continu pour la partie légale/modération.**

---

## Phase 8 — Monétisation (dernière étape, pas urgente)

**Objectif : rendre le projet soutenable financièrement, sans casser l'équilibre PvE-first.**

- [ ] **Définir l'offre de boutique** — cosmétique, gain de temps, rançon de héros capturé (cohérent avec le lore) : jamais de pay-to-win direct vu ton positionnement
- [ ] **Intégrer un moyen de paiement** (Stripe est le plus simple à intégrer techniquement)
- [ ] **Tester la boutique en interne** avant ouverture large

**Durée estimée : à n'aborder que si les phases précédentes confirment que le projet a une vraie communauté.**

---

## Résumé — ce qui vient tout de suite

1. **Phase 0** : on finalise le GDD ensemble (prochaine session naturelle)
2. **Phase 1** : setup VSCode + Claude Code + GitHub + Docker local
3. **Phase 2** : premier audit du repo OGameX avec moi

Le reste s'enchaîne progressivement — pas besoin de tout voir d'un coup, on avance brique par brique à ton rythme.

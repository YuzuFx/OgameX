# CLAUDE.md

Ce fichier donne à Claude Code le contexte nécessaire pour travailler sur ce projet.

## Contexte du projet

La base technique est **OGameX** (fork de https://github.com/lanedirt/OGameX), un clone open-source d'Ogame en Laravel/PHP. Ce fork est en cours de transformation en un jeu navigateur **heroic fantasy, PvE-first**, avec un système de héros/gouverneur, une jauge de prestige d'empire, et du contenu PvE contre des bots.

**Statut actuel : Phase 0 (cadrage du concept)** — le reskin et les mécaniques différenciantes n'ont pas encore été implémentés. Le code de ce repo est encore celui d'OGameX tel quel.

## Documents de conception à lire avant toute tâche liée au jeu

Ces trois documents dans `docs/` définissent la vision, les mécaniques et l'univers du projet. Les lire avant de proposer ou d'implémenter une fonctionnalité liée au gameplay, au lore, ou à la roadmap :

- **[docs/feuille-de-route-projet-jeu.md](docs/feuille-de-route-projet-jeu.md)** — Roadmap complète du projet par phases (cadrage, setup, audit technique, reskin, mécaniques différenciantes, hébergement, contenu, ouverture publique, monétisation). À consulter pour savoir où en est le projet et ce qui vient ensuite.
- **[docs/game-design-document.md](docs/game-design-document.md)** — Game Design Document (GDD) : piliers de gameplay, boucles courte/moyenne/longue, lexique de conversion Ogame→jeu fantasy (ressources, bâtiments, recherches, unités), systèmes détaillés (production, combat, Mode de Guerre, héros/gouverneur, prestige d'empire, craft/forge, bots PvE, alliance). Document vivant, plusieurs sections encore marquées **[TBD]**.
- **[docs/lore.md](docs/lore.md)** — Univers narratif : cosmologie (Straumar, Neuf Sphères), la Rupture (chronologie), les Vahrun, factions ennemies, races jouables, structure cartographique (Régions/Contrées/Éclats), noms encore en discussion.

Ces documents sont vivants et évolueront au fil du projet — s'y référer plutôt que de supposer des mécaniques ou noms non confirmés.

## Principes de travail

- **Zéro code manuel côté utilisateur** : Gregory ne code pas lui-même, tout passe par Claude Code.
- **V1 minimum jouable d'abord** : ne pas se disperser sur des fonctionnalités listées comme "plus tard" dans la roadmap tant que la V1 n'est pas cadrée/posée.
- **Les formules Ogame/OGameX de base restent héritées en V1** (production, coûts, combat) — seul l'habillage (noms, thème) change dans un premier temps ; l'équilibrage viendra en Phase 6.
- Avant toute modification structurelle du code OGameX, privilégier l'audit et les tâches pilotes (Phase 2 de la roadmap) plutôt que des changements larges non validés.
- **Contenu de jeu conçu en français en premier** (noms d'archétypes, de talents, d'objets, libellés d'interface) — décidé le 2026-09-15. L'internationalisation (anglais et autres langues, infrastructure déjà en place via `resources/lang/`) viendra plus tard. Le code lui-même (colonnes, classes, machine names) reste en anglais/technique, seul le contenu affiché au joueur est en français dès maintenant.

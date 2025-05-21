# Esportify

Plateforme de gestion d'événements et de tournois eSport.  
Permet aux **joueurs** de s'inscrire aux événements et aux **organisateurs** de les créer et les gérer.

---

## Installation locale avec Docker

### 1. Cloner le dépôt

```bash
git clone https://github.com/MarcoB1991/esportify.git
cd esportify
```

### 2. Lancer Docker

```bash
docker-compose up -d
```

### 3. Importer la base de données

```bash
docker exec -i esportify-db mysql -u esport -p esportify_db < esportify.sql
```

### 4. Accéder depuis le navigateur

Aller sur : [http://localhost:8082]

---

## Accès de test

| Rôle            | Email                  | Mot de passe   |
|-----------------|------------------------|----------------|
| Admin           | admin@esportify.com    | adminpass      |
| Organisateur    | luna@esportify.com     | 123            |
| Joueur          | utente4@esempio.com    | 123            |

---

## Structure du projet

- `src/` 
- `index.php`, `login.php`, `register.php`, `....`
- `admin/`, `organizer/`
- `includes/` – composants communs
- `assets/` – CSS, images
- `config/` – base de données
- `sql/` – structure + données SQL

---

## Déploiement

Vous pouvez héberger le site avec :

- Railway.app (recommandé)
- Render.com
- Hébergement PHP/MySQL

---

## Documentation incluse

- `manuel_utilisateur.pdf`
- `charte_graphique.pdf`
- `documentation_technique.pdf`
- `mockup.pdf`
- `wireframe.pdf`
- `wireframe.png`

---

## Crédits

Réalisé par Marco Bertello – 2025  
Projet final **Développeur Web et Web Mobile – ECF 2025 - Studi**
# Storefront Child – Cluizel Paris

Thème enfant de Storefront pour la SAé 3.01 (BUT2 MMI) : site click & collect WordPress / WooCommerce.

- **Dev** : https://sae301.ddev.site/
- **Prod** : https://mmi.unilim.fr/~gautier58/sae301/

---

## Processus de mise à jour de la Prod

On modifie **toujours en Dev**, jamais directement en Prod. Selon ce qui a changé, on applique une ou plusieurs des étapes ci-dessous.

### 1. Le thème enfant a changé (CSS, PHP, templates)

Le code passe par GitHub.

```bash
# Dev
git add .
git commit -m "[IT-N] Description de la modification"
git push

# Prod (SSH, dossier du thème enfant)
git pull
```

### 2. Un plugin a été ajouté ou mis à jour

Les plugins sont copiés du Dev vers la Prod.

```bash
# Dev
rsync -avz wp-content/plugins/ gautier58@mmi.unilim.fr:~/public_html/sae301/wp-content/plugins/

# Prod (si nouveau plugin)
wp plugin activate NOM_DU_PLUGIN
```

### 3. Le contenu ou les réglages ont changé (produits, pages, menus, options)

La base de données du Dev remplace celle de la Prod. **Toujours sauvegarder la Prod avant.**

```bash
# Dev
ddev wp db export sae301-dev.sql
scp sae301-dev.sql gautier58@mmi.unilim.fr:~/
rsync -avz wp-content/uploads/ gautier58@mmi.unilim.fr:~/public_html/sae301/wp-content/uploads/

# Prod
wp db export ~/sauvegarde-prod.sql
wp db import ~/sae301-dev.sql
wp search-replace 'https://sae301.ddev.site' 'https://mmi.unilim.fr/~gautier58/sae301' --all-tables-with-prefix --skip-columns=guid
wp rewrite flush --hard
```

### 4. Après chaque mise en production

1. Vérifier le front end (en navigation privée) et le back office de la Prod.
2. Taguer l'itération : `git tag -a it-N -m "Itération N"` puis `git push origin it-N`.
3. Ajouter une ligne en haut du journal ci-dessous.

En cas de problème : `wp db import ~/sauvegarde-prod.sql` restaure la base de données, `git checkout it-PRECEDENT` restaure le thème.

---

## Journal des mises en production

La plus récente en haut.

| Date       | Itération | Modifications mises en production                                                                                                                                                                            | Étapes appliquées   | Tag    | Vérifié |
| ---------- | --------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------- | ------ | ------- |
| 19/10/2026 | IT-3      | Produits variables (Format, Supplément, Emballage) avec prix et niveaux de stock pour Plantation Los Anconès, Coffret Mendiants N°25 et Coffret Cappuccino ; sélecteur d'options aux couleurs de la maquette | 1 et 3              | `it-3` |         |
| 16/10/2026 | IT-2      | Apparence générale, catalogue (3 catégories, 9 produits simples), grille de la carte, fiche produit                                                                                                          | 1 et 3              | `it-2` | Oui     |
| 16/10/2026 | IT-1      | Mise en place des environnements de Dev et de Prod, thème enfant Storefront                                                                                                                                  | Premier déploiement | `it-1` | Oui     |

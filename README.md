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

| Date       | Itération | Modifications mises en production                                                                                                                                                                                                                                              | Étapes appliquées                                                                        | Tag     | Vérifié |
| ---------- | --------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ---------------------------------------------------------------------------------------- | ------- | ------- |
| 21/10/2026 | IT-15     | Frais de préparation : réglages (activer, libellé, montant, seuil) dans WooCommerce › Frais de préparation, frais ajoutés au calcul du panier sous le seuil, message « Plus que X € » dans le panier et la validation                                                          | 1 et 3                                                                                   | `it-15` | Oui     |
| 21/10/2026 | IT-14     | Avis clients : section « Avis clients » (note moyenne, répartition, cartes d'avis, « Achat vérifié »), étoiles sous le titre, formulaire à étoiles réservé aux acheteurs (vérifié côté serveur)                                                                                | 1 et 3                                                                                   | `it-14` | Oui     |
| 21/10/2026 | IT-13     | Ventes croisées : produits suggérés et ventes croisées associés dans le back office, affichés en priorité sur la fiche (« Pour compléter votre commande ») et dans le panier (« Vous aimerez aussi »)                                                                          | 1 et 3                                                                                   | `it-13` | Oui     |
| 21/10/2026 | IT-12     | Taxonomie « Collections » indépendante des catégories (admin, colonne, filtre, image), 3 collections avec 2 produits ou plus, page de chaque collection en grille, page « Nos collections », lien dans le menu                                                                 | 1 et 3                                                                                   | `it-12` | Oui     |
| 21/10/2026 | IT-11     | Équipe : type de contenu « Membres de l'équipe » (champs SCF, photo, ordre), page Équipe réglable, gabarits liste et profil, lien dans le menu et le pied de page, 3 profils                                                                                                   | 1 et 3                                                                                   | `it-11` | Oui     |
| 20/10/2026 | IT-10     | Promotions : codes promo (pourcentage et montant fixe sur deux produits), champ « Code promo » dans le panier, ligne de réduction avec « Retirer », messages d'erreur (erroné, expiré, indisponible)                                                                           | 1 et 3                                                                                   | `it-10` | Oui     |
| 20/10/2026 | IT-9      | Commandes : annulation par le client, e-mails d'avancement selon le paiement, e-mails « annulée » et « remboursée », colonne « Réception » dans l'admin, produit simple ajouté                                                                                                 | 1 et 3                                                                                   | `it-9`  | Oui     |
| 20/10/2026 | IT-8      | E-mails transactionnels aux couleurs de Cluizel (en-tête, pied, styles), textes des e-mails client, instructions de retrait, nouvel e-mail « Commande prête à retirer », choix du paiement à distance (virement, PayPal, Wero)                                                 | 1 et 3                                                                                   | `it-8`  | Oui     |
| 19/10/2026 | IT-7      | Compte client : création de compte et connexion, espace client (tableau de bord avec suivi de commande, Mes commandes, Mes adresses, Mes informations, suppression du compte), statut « Prête à retirer »                                                                      | 1 et 3                                                                                   | `it-7`  | Oui     |
| 19/10/2026 | IT-6      | Instructions pour le retrait réglables dans WooCommerce › Retrait en boutique, affichées pendant la validation, sur la page de confirmation et dans l'e-mail client ; page « Commande reçue » conforme à la maquette                                                           | 1 et 3                                                                                   | `it-6`  | Oui     |
| 19/10/2026 | IT-5      | Paiements « Au retrait » (réservé au retrait en boutique) et « Par virement » ; zone France métropolitaine avec retrait en boutique gratuit, livraison à domicile (6,90 €) et express (12,90 €) ; pages Panier et Validation de la commande conformes à la maquette ; page CGV | 1 et 3                                                                                   | `it-5`  | Oui     |
| 19/10/2026 | IT-4      | Champs personnalisés SCF « Composition et valeurs nutritionnelles » (cacao, poids net, origine, valeurs pour 100 g) renseignés sur 3 produits et affichés avant le prix                                                                                                        | 1 et 3 (déployée avec IT-3)                                                              | `it-4`  | Oui     |
| 19/10/2026 | IT-3      | Produits variables (Format, Supplément, Emballage) avec prix et niveaux de stock pour 3 produits ; sélecteur d'options aux couleurs de la maquette                                                                                                                             | 1 et 3 (mise en ligne retardée : serveur mmi.unilim.fr indisponible, déployée avec IT-4) | `it-3`  | Oui     |
| 16/10/2026 | IT-2      | Apparence générale, catalogue (3 catégories, 9 produits simples), grille de la carte, fiche produit                                                                                                                                                                            | 1 et 3                                                                                   | `it-2`  | Oui     |
| 16/10/2026 | IT-1      | Mise en place des environnements de Dev et de Prod, thème enfant Storefront                                                                                                                                                                                                    | Premier déploiement                                                                      | `it-1`  | Oui     |

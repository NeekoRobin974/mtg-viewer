## Exercice 0

**Pb rencontré :**
Pour le 1er test d'import, j'ai constaté que le processus devenait de plus en plus lent selon le nombre de cartes à importer.
La fonction in_array(uuid, uuidInDatabase) était appelée pour chaque ligne du csv. Mais $uuidInDatabase contient des dizaines de milliers d'entrées.

**Solution :**
Fonction array_flip() sur le tableau des UUIDs existants. Elle transforme les valeurs en cléfs, ce qui est plus rapide.

Ajout d'une option --limit à la commande pour que ce soit plus simple de tester et choisir cbn de cartes on veut importer.

## Exercice 1 : Ajout de logs

**Pour l'import :**
J'ai utilisé le service LoggerInterface standard de Symfony injecté dans la commande. J'ai ajouté des logs de niveau INFO pour marquer le début et la fin, et ERROR si le fichier est introuvable ou si une exception survient.

**Pour l'API :**
J'ai créé un Event Listener nommé ApiLogListener. Il intercepte toutes les requêtes entrantes de l'application. Si l'url commence par /api, il enregistre automatiquement la méthode http et le chemin.

## Exercice 2

**Backend :**

Méthode searchByName dans le CardRepository. Limite de 20 résultats comme demandé.

/!\ à mettre la route /api/card/search avant la route/api/card/{uuid} dans le contrôleur, sinon, Symfony interprétait le mot "search" comme étant un paramètre {uuid} et essayait de trouver une carte nommée "search", ce qui renvoyait une 404.

**Frontend :**

Barre de recherche qui lance la requête sans avoir besoin de cliquer sur un bouton.

Pour éviter de spam l'api à chaque fois que l'utilisateur appuie sur une touche, j'ai demandé à l'ia de me mettre une temporisation si c'était possible. La requête ne part que si l'utilisateur arrête d'écrire pendant ce court laps de temps.

La recherche se déclenche qu'à partir de 3 caractères pour éviter les résultats trop nombreux et non pertinents.

## Exercice 3 :

**Backend :**
Pour le filtrage, j'ai ajouté un paramètre optionnel setCode aux routes de listing et de recherche.

Méthode spécifique getAllSetCodes en utilisant DISTINCT pour récupérer que les codes d'extension uniques présents en base = plus performant.

**Frontend :**

J'ai ajouté un menu déroulant (`<select>`) sur les pages de recherche et de listing.

Dès qu'on change la valeur du select, une nouvelle requête est automatiquement lancée vers l'api.

Le filtre se combine avec la recherche textuelle.

## Exercice 4 :

**Backend :**

On renvoie maintenant un objet { data: [...], meta: { total, page, ... } }. Le front connaît le nombre total de pages disponibles.

**Frontend :**

Boutons Précédent/Suivant.

Si il y a un changement de filtre, on remet à la page 1 pour éviter de se retrouver sur une page vide.

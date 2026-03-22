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

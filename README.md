# test-dev

Un stagiaire à créer le code contenu dans le fichier src/Controller/Home.php

Celui permet de récupérer des urls via un flux RSS ou un appel à l’API NewsApi.
Celles ci sont filtrées (si contient une image) et dé doublonnées.
Enfin, il faut récupérer une image sur chacune de ces pages.

Le lead dev n'est pas très satisfait du résultat, il va falloir améliorer le code.

Pratique :

1. Revoir complètement la conception du code (découper le code afin de pouvoir ajouter de nouveaux flux simplement)

Questions théoriques :

1. Que mettriez-vous en place afin d'améliorer les temps de réponses du script
2. Comment aborderiez-vous le fait de rendre scalable le script (plusieurs milliers de sources et images)

Réponses aux questions théoriques :

1.
L'utilisation du cache pour éviter d'effectuer toujours la même requêtes à intervalle de temps trop court.

2.
Architecture modulaire : On peut diviser le code en modules ou services distincts, chacun responsable d'une tâche spécifique. Par exemple, vous pourriez avoir un service de récupération de données, un service de traitement d'images, etc. Cela permettra d'ajouter facilement de nouvelles sources ou fonctionnalités sans perturber l'ensemble du système.

Gestion de la file d'attente : Utiliser une file d'attente (comme RabbitMQ + Messenger ) pour gérer les tâches de manière asynchrone. Chaque nouvelle source à traiter est mise en file d'attente, puis les travailleurs (workers) récupèrent et traitent ces tâches en parallèle.

Scaling horizontal : Si le volume de travail augmente, envisagez d'ajouter des serveurs ou des conteneurs supplémentaires pour répartir la charge. Vous pouvez utiliser des technologies comme Docker et Kubernetes pour gérer le déploiement et la mise à l'échelle des conteneurs.
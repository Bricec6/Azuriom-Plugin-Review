<?php

return [
    'plugin' => [
        'name' => "Review",
    ],
    'index' => [
        'title' => "Avis",
    ],
    'imports' => [
        'title' => "Import des avis",
    ],
    'settings' => [
        'title' => "Configuration",
        'display' => [
            'title' => "Affichage",
            'per-page' => "Nombre d'avis par page",
            'imported' => "Afficher les avis importés dans la liste",
            'average' => "Compter les avis importés dans la note moyenne",
        ],
    ],
    'sources' => [
        'title' => "Sources d'import",
        'description' => "Importez les avis déjà publiés sur les annuaires où votre serveur est référencé.",
        'token' => "Token API",
        'test' => "Tester",
        'sync' => "Synchroniser les avis",
        'enable' => "Importer les avis de cet annuaire",
        'vote-token' => "Token récupéré depuis le plugin Vote (:site).",
        'vote-placeholder' => "Token du plugin Vote utilisé",
        'missing-token' => "Aucun token API n'est configuré pour cet annuaire.",
        'test-success' => ":count avis trouvé(s) sur cet annuaire.",
        'sync-success' => ":count avis importé(s).",
        'imported-count' => "{1} :count avis importé|[2,*] :count avis importés",
        'last-sync' => "Dernière synchronisation : :date",
        'unsupported' => [
            'serveur-prive' => "L'API de cet annuaire donne le nombre d'avis, pas leur contenu.",
            'serveur-minecraft' => "Cet annuaire n'expose aucune API sur ses avis.",
        ],
    ],
    'permissions' => [
        "create" => "Créer un avis",
        "delete" => [
            "other" => "Supprimer un avis",
        ],
    ],
    'logs' => [
        "reviews-reviews" => [
            "created" => "Avis #:id créé",
            "updated" => "Avis #:id modifié",
            "deleted" => "Avis #:id supprimé",
        ],
        'settings' => "Configuration des avis modifiée",
        'synced' => ":count avis importé(s) depuis :source",
    ],
    'table' => [
        'rating' => 'Note',
        'source' => 'Source',
        'local' => 'Site',
    ],
    'achievement' => [
        'post' => 'Avis publié',
        'five_star' => 'Avis 5 étoiles publié',
    ],
    'support' => "Support Discord",
    "serveurliste" => "Liste des meilleurs serveurs",
    "contribute" => "Contribuer",
];

<?php
/**
 * Fichier errors-codes.php
 * Retourne un tableau des codes erreurs Synology
 */
return [
    'AudioStation' => [
        500 => [
            'en' => 'Unknow error',
            'fr' => 'Erreur inconnue au bataillon !',
        ],
    ],
    'DownloadStation' => [
        400 => [
            'en' => 'File upload failed',
            'fr' => 'Le téléchargement du fichier a échoué',
        ],
        401 => [
            'en' => 'Max number of tasks reached',
            'fr' => 'Nombre maximum de tâches atteintes',
        ],
        402 => [
            'en' => 'Destination denied',
            'fr' => 'Destination refusée',
        ],
        403 => [
            'en' => 'Destination does not exist',
            'fr' => "La destination n'existe pas",
        ],
        404 => [
            'en' => 'Invalid task id',
            'fr' => "Identifiant de la tâche incorrect",
        ],
        405 => [
            'en' => 'Invalid task action',
            'fr' => "Action de tâche invalide",
        ],
        406 => [
            'en' => 'No default destination',
            'fr' => 'Pas de destination par défaut',
        ],
        407 => [
            'en' => 'Set destination failed',
            'fr' => 'Impossible de définir la destination',
        ],
        408 => [
            'en' => 'File does not exist',
            'fr' => "Le fichier n'existe pas",
        ],
    ],
    'VideoStation' => [
        409 => [
            'en' => 'Unknow error',
            'fr' => 'Erreur inconnue au bataillon !',
        ],
    ],

    100 => [
        'en' => 'Unknown error',
        'fr' => 'Erreur inconnue au bataillon !',
    ],
    101 => [
        'en' => 'Invalid parameter',
        'fr' => 'Paramètre invalide',
    ],
    102 => [
        'en' => 'The requested API does not exist',
        'fr' => 'L’API demandée n’existe pas'
    ],
    103 => [
        'en' => 'The requested method does not exist',
        'fr' => 'La méthode demandée n’existe pas',
    ],
    104 => [
        'en' => 'The requested version does not support the functionality',
        'fr' => 'La version demandée ne supporte pas la fonctionnalité'
    ],
    105 => [
        'en' => 'The logged in session does not have permission',
        'fr' => 'La session enregistrée n’a pas d’autorisation'
    ],
    106 => [
        'en' => 'Session timeout',
        'fr' => 'Session échue'
    ],
    107 => [
        'en' => 'Session interrupted by duplicate login',
        'fr' => 'Session interrompue pour double connexion',
    ],
    119 => [
        'en' => 'Unknow error',
        'fr' => 'Erreur inconnue au bataillon !',
    ],
    400 => [
        'en' => 'No such account or incorrect password',
        'fr' => 'Aucun compte ou mot de passe incorrect',
    ],
    401 => [
        'en' => 'Account disabled',
        'fr' => 'Compte désactivé',
    ],
    402 => [
        'en' => 'Permission denied',
        'fr' => 'Permission refusée',
    ],
    403 => [
        'en' => '2-step verification code required',
        'fr' => 'Code de vérification en deux étapes requis',
    ],
    404 => [
        'en' => 'Failed to authenticate 2-step verification code',
        'fr' => 'Échec de l’authentification du code de vérification en 2 étapes',
    ],
    599 => [
        'en' => 'No such task of the file operation',
        'fr' => 'Aucune tâche pour ce type d’opération de fichier',
    ],
    600 => [
        'en' => 'Unknown error',
        'fr' => 'Erreur inconnue chez Synology.',
    ],
    800 => [
        'en' => 'A folder path of favorite folder is already added to user’s favorites.',
        'fr' => 'Le chemin est déjà dans les favoris.',
    ],
    801 => [
        'en' => 'A folder path of favorite folder is already added to user’s favorites.',
        'fr' => 'Le nom de favoris existe déjà.',
    ],
    802 => [
        'en' => 'There are too many favorites to be added.',
        'fr' => 'Limite de favoris atteinte.',
    ],
    1800 => [
        'en' => 'There is no Content-Length information in the HTTP header or the received size doesn’t match
        the value of Content-Length information in the HTTP header.',
        'fr' => 'Il manque le Header Content-Length',
    ],
    1801 => [
        'en' => 'Wait too long, no date can be received from client (Default maximum wait time is 3600 seconds).',
        'fr' => 'Délai d’une minute dépassé.',
    ],
    1100 => [
        'en' => 'Failed to create a folder. More information in errors object.',
        'fr' => 'Impossible de créer le dossier. Voir le contenu de errors',
    ],
    1101 => [
        'en' => 'The number of folders to the parent folder would exceed the system limitation.',
        'fr' => 'Nombre de dossiers dépassé dans ce dossier.',
    ],
    1802 => [
        'en' => 'No filename information in the last part of file content.',
        'fr' => 'Aucun nom de fichier détecté dans la dernière partie du nom.',
    ],
    1200 => [
        'en' => 'Failed to rename it. More information in errors object.',
        'fr' => 'Impossible de renommer. Voir les détails dans errors',
    ],
    1803 => [
        'en' => 'Upload connection is cancelled.',
        'fr' => 'La connexion a été annulée lors de l’upload.',
    ],
    1804 => [
        'en' => 'Failed to upload too big file to FAT file system.',
        'fr' => 'La taille du fichier est trop volumineuse pour un système de fichier FAT.',
    ],
    1805 => [
        'en' => 'Can’t overwrite or skip the existed file, if no overwrite parameter is given.',
        'fr' => 'Impossible d’écraser un fichier si aucune directive n’est donnée.',
    ],
    2000 => [
        'en' => 'Sharing link does not exist.',
        'fr' => 'Le lien partagé n’existe pas.',
    ],
    2001 => [
        'en' => 'Cannot generate sharing link because too many sharing links exist.',
        'fr' => 'Limite de liens partagés atteinte.',
    ],
    2002 => [
        'en' => 'Failed to access sharing links.',
        'fr' => 'Impossible d’accéder au lien partagé.',
    ]
];

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
    'FileStation' => [
        400 => [
            'en' => 'Invalid parameter of file operation.',
            'fr' => "Paramètre invalide pour l'opération de fichier.",
        ],
        401 => [
            'en' => 'Unknown error of file operation.',
            'fr' => "Erreur inconnue pour l'opération de fichier.",
        ],
        402 => [
            'en' => 'System is too busy.',
            'fr' => "Le système est trop occupé.",
        ],
        403 => [
            'en' => 'Invalid user does this file operation.',
            'fr' => "L'utilisateur invalide pour cette opération de fichier.",
        ],
        404 => [
            'en' => 'Invalid user and group does this file operation.',
            'fr' => "Groupe invalide pour cette opération de fichier.",
        ],
        405 => [
            'en' => 'Invalid group does this file operation.',
            'fr' => "Utilisateur et groupe invalides pour cette opération de fichier.",
        ],
        406 => [
            'en' => 'Can’t get user/group information from the account server.',
            'fr' => "Impossible d'obtenir les informations utilisateur/groupe du serveur de compte.",
        ],
        407 => [
            'en' => 'Operation not permitted.',
            'fr' => "Opération interdite.",
        ],
        408 => [
            'en' => 'No such file or directory.',
            'fr' => "Aucun fichier ou répertoire de ce nom.",
        ],
        409 => [
            'en' => 'Non-supported file system.',
            'fr' => "Système de fichiers non pris en charge.",
        ],
        410 => [
            'en' => 'Failed to connect internet-based file system (ex: CIFS).',
            'fr' => "Impossible de connecter le système de fichiers basé sur Internet (ex: CIFS).",
        ],
        411 => [
            'en' => 'Read-only file system.',
            'fr' => "Système de fichiers en lecture seule.",
        ],
        412 => [
            'en' => 'Filename too long in the non-encrypted file system.',
            'fr' => "Nom de fichier trop long dans le système de fichiers non crypté.",
        ],
        413 => [
            'en' => 'Filename too long in the encrypted file system.',
            'fr' => "Nom de fichier trop long dans le système de fichiers crypté.",
        ],
        414 => [
            'en' => 'File already exists.',
            'fr' => "Le fichier existe déjà.",
        ],
        415 => [
            'en' => 'Disk quota exceeded.',
            'fr' => "Quota de disque dépassé.",
        ],
        416 => [
            'en' => 'No space left on device.',
            'fr' => "Pas d'espace disponible sur le périphérique.",
        ],
        417 => [
            'en' => 'Input/output error.',
            'fr' => "Erreur d'entrée/sortie.",
        ],
        418 => [
            'en' => 'Illegal name or path.',
            'fr' => "Nom ou chemin illégal.",
        ],
        419 => [
            'en' => 'Illegal file name.',
            'fr' => "Nom de fichier illégal.",
        ],
        420 => [
            'en' => 'Illegal file name on FAT file system.',
            'fr' => "Nom de fichier illégal sur le système de fichiers FAT.",
        ],
        421 => [
            'en' => 'Device or resource busy.',
            'fr' => "Périphérique ou ressource occupé."
        ],
        599 => [
            'en' => 'No such task of the file operation.',
            'fr' => "Tâche inconnue pour l'opération de fichier."
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
        900 => [
            'en' => 'Failed to delete file(s)/folder(s). More information in errors object.',
            'fr' => "Échec de la suppression de fichiers/dossiers. Plus d'informations dans l'objet errors.",
        ],
        1000 => [
            'en' => 'Failed to copy files/folders. More information in errors object.',
            'fr' => "Échec de la copie de fichiers/dossiers. Plus d'informations dans l'objet errors.",
        ],
        1001 => [
            'en' => 'Failed to move files/folders. More information in errors object.',
            'fr' => "Impossible de déplacer les fichiers/dossiers. Plus d'informations dans l'objet errors.",
        ],
        1002 => [
            'en' => 'An error occurred at the destination. More information in errors object.',
            'fr' => "Une erreur s'est produite à la destination. Plus d'informations dans l'objet errors.",
        ],
        1003 => [
            'en' => 'Cannot overwrite or skip the existing file because no overwrite parameter is given.',
            'fr' => "Impossible de remplacer ou d'ignorer le fichier existant car aucun paramètre de remplacement n'est donné.",
        ],
        1004 => [
            'en' => 'File cannot overwrite a folder with the same name, or folder cannot overwrite a file with the same name.',
            'fr' => "Le fichier ne peut pas écraser un dossier portant le même nom ou le dossier ne peut pas remplacer un fichier du même nom.",
        ],
        1006 => [
            'en' => 'Cannot copy/move file/folder with special characters to a FAT32 file system.',
            'fr' => "Impossible de copier/déplacer un fichier/dossier avec des caractères spéciaux vers un système de fichiers FAT32.",
        ],
        1007 => [
            'en' => 'Cannot copy/move a file bigger than 4G to a FAT32 file system.',
            'fr' => "Impossible de copier/déplacer un fichier plus grand que 4G vers un système de fichiers FAT32.",
        ],
        1100 => [
            'en' => 'Failed to create a folder. More information in errors object.',
            'fr' => "Impossible de créer un dossier. Plus d'informations dans l'objet errors.",
        ],
        1101 => [
            'en' => 'The number of folders to the parent folder would exceed the system limitation.',
            'fr' => "Le nombre de dossiers dans le dossier parent dépasserait la limite du système.",
        ],
        1200 => [
            'en' => 'Failed to rename it. More information in errors object.',
            'fr' => "Impossible de renommer. Voir les détails dans errors.",
        ],
        1300 => [
            'en' => 'Failed to compress files/folders.',
            'fr' => "Échec de la compression des fichiers/dossiers.",
        ],
        1301 => [
            'en' => 'Cannot create the archive because the given archive name is too long.',
            'fr' => "Impossible de créer l'archive car le nom d'archive donné est trop long.",
        ],
        1400 => [
            'en' => 'Failed to extract files of archive.',
            'fr' => "Impossible d'extraire les fichiers de l'archive.",
        ],
        1401 => [
            'en' => 'Cannot open the file as archive.',
            'fr' => "Impossible d'ouvrir le fichier en tant qu'archive.",
        ],
        1402 => [
            'en' => 'Failed to read archive data error.',
            'fr' => "Impossible de lire l'erreur de données d'archive.",
        ],
        1403 => [
            'en' => 'Wrong password for archive.',
            'fr' => "Mot de passe de l'archive incorrect.",
        ],
        1404 => [
            'en' => 'Failed to get the file and dir list in an archive.',
            'fr' => "Impossible d'obtenir le fichier et la liste des répertoires dans une archive.",
        ],
        1405 => [
            'en' => 'Failed to find the item ID in an archive file.',
            'fr' => "Impossible de trouver l'ID d'élément dans un fichier d'archive.",
        ],
        1800 => [
            'en' => 'There is no Content-Length information in the HTTP header or the received size doesn’t match the value of Content-Length information in the HTTP header.',
            'fr' => "Il n'y a pas d'information Content-Length dans l'en-tête HTTP ou le reçu taille ne correspond pas à la valeur des informations Content-Length dans le HTTP entête."
        ],
        1801 => [
            'en' => 'Wait too long, no date can be received from client (Default maximum wait time is 3600 seconds).',
            'fr' => "Attendez trop longtemps, aucune date ne peut être reçue du client (le temps d'attente maximum par défaut est de 3600 secondes)."
        ],
        1802 => [
            'en' => 'No filename information in the last part of file content.',
            'fr' => "Aucune information de nom de fichier dans la dernière partie du contenu du fichier."
        ],
        1803 => [
            'en' => 'Upload connection is cancelled.',
            'fr' => "La connexion de téléchargement est annulée."
        ],
        1804 => [
            'en' => 'Failed to upload too big file to FAT file system.',
            'fr' => "Impossible de télécharger un fichier trop volumineux sur le système de fichiers FAT."
        ],
        1805 => [
            'en' => 'Can’t overwrite or skip the existed file, if no overwrite parameter is given.',
            'fr' => "Impossible de remplacer ou d'ignorer le fichier existant, si aucun paramètre de remplacement n'est donné."
        ],
        2000 => [
            'en' => 'Sharing link does not exist.',
            'fr' => "Le lien partagé n'existe pas.",
        ],
        2001 => [
            'en' => 'Cannot generate sharing link because too many sharing links exist..',
            'fr' => "Impossible de générer un lien partagé, car trop de liens existent.",
        ],
        2002 => [
            'en' => 'Failed to access sharing links.',
            'fr' => "Impossible d'accéder au liens partagés.",
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
    ]
];

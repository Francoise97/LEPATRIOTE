# GUIDE D'INSTALLATION PHP 8.2+ SÉPARÉMENT
## Pour Laravel 11 - Le Patriote

---

## 📥 ÉTAPE 1 : Télécharger PHP 8.2

1. **Ouvrez ce lien dans votre navigateur** :
   https://windows.php.net/download#php-8.2

2. **Cherchez la section "PHP 8.2.27"** (ou version 8.2.x plus récente)

3. **Cliquez sur "VS16 x64 Non Thread Safe"**
   - C'est un fichier ZIP
   - Taille : environ 25 MB
   - Nom du fichier : `php-8.2.27-Win32-vs16-x64-NTS.zip`

4. **Sauvegardez sur votre Bureau** ou dans `C:\Users\DELL\Downloads`

---

## 📂 ÉTAPE 2 : Créer le dossier d'installation

1. **Ouvrez l'explorateur de fichiers**
2. **Allez dans C:\**
3. **Créez un nouveau dossier** nommé : `php82`
   - Chemin complet : `C:\php82`

---

## 📦 ÉTAPE 3 : Extraire PHP

1. **Ouvrez le fichier ZIP téléchargé** (double-clic)
2. **Sélectionnez tout le contenu** (Ctrl+A)
3. **Copiez tout** (Ctrl+C)
4. **Allez dans C:\php82**
5. **Collez tout** (Ctrl+V)

Vous devriez voir des fichiers comme :
- php.exe
- php.ini-development
- php.ini-production
- ext/ (dossier avec les extensions)
- etc.

---

## ⚙️ ÉTAPE 4 : Configurer PHP

1. **Dans C:\php82**, trouvez le fichier `php.ini-development`
2. **Faites une copie** de ce fichier
3. **Renommez la copie en** : `php.ini`

---

## ✅ ÉTAPE 5 : Vérifier l'installation

Ouvrez un terminal (PowerShell ou CMD) et tapez :

```bash
C:\php82\php.exe -v
```

Vous devriez voir quelque chose comme :
```
PHP 8.2.27 (cli) (built: ...) (NTS Visual C++ 2019 x64)
```

---

## 🎯 ÉTAPE 6 : Configurer Composer pour utiliser PHP 8.2

Une fois PHP 8.2 installé et vérifié, dites-moi et je configurerai Composer pour l'utiliser.

---

## 📋 RÉSUMÉ

- Télécharger PHP 8.2.27 VS16 x64 NTS (ZIP)
- Créer dossier C:\php82
- Extraire le contenu dans C:\php82
- Copier php.ini-development → php.ini
- Vérifier avec `C:\php82\php.exe -v`

**Dites-moi quand vous avez terminé ces étapes !** 🚀

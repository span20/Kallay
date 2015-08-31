<?php

/**
 * language pack
 * @author Logan Cai (cailongqun [at] yahoo [dot] com [dot] cn)
 * @link www.phpletter.com
 * @since 22/April/2007
 *
 */
define('DATE_TIME_FORMAT', 'Y.M.d H:i:s');

define('MENU_SELECT',   'Kiválasztás');
define('MENU_DOWNLOAD', 'Letöltés');
define('MENU_PREVIEW',  'Előnézet');
define('MENU_RENAME',   'Átnevezés');
define('MENU_EDIT',     'Szerkesztés');
define('MENU_CUT',      'Kivágás');
define('MENU_COPY',     'Másolás');
define('MENU_DELETE',   'Törlés');
define('MENU_PLAY',     'Lejátszás');
define('MENU_PASTE',    'Beszúrás');

//Label
//Top Action
define('LBL_ACTION_REFRESH',    'Frissítés');
define("LBL_ACTION_DELETE",     'Törlés');
define('LBL_ACTION_CUT',        'Kivágás');
define('LBL_ACTION_COPY',       'Másolás');
define('LBL_ACTION_PASTE',      'Beillesztés');
define('LBL_ACTION_CLOSE',      'Bezárás');
define('LBL_ACTION_SELECT_ALL', 'Összes kiválasztása');

//File Listing
define('LBL_NAME',     'Név');
define('LBL_SIZE',     'Méret');
define('LBL_MODIFIED', 'Módosítva');

//File Information
define('LBL_FILE_INFO',     'Fájl információ:');
define('LBL_FILE_NAME',     'Név:');	
define('LBL_FILE_CREATED',  'Létrehozva:');
define("LBL_FILE_MODIFIED", 'Módosítva:');
define("LBL_FILE_SIZE",     'Fájl méret:');
define('LBL_FILE_TYPE',     'Fájl típus:');
define("LBL_FILE_WRITABLE", 'Írható?');
define("LBL_FILE_READABLE", 'Olvasható?');

//Folder Information
define('LBL_FOLDER_INFO',         'Mappa információ');
define("LBL_FOLDER_PATH",         'Elérési út:');
define('LBL_CURRENT_FOLDER_PATH', 'Jelenlegi elérési út:');
define("LBL_FOLDER_CREATED",      'Létrehozva:');
define("LBL_FOLDER_MODIFIED",     'Módosítva:');
define('LBL_FOLDER_SUDDIR',       'Almappák:');
define("LBL_FOLDER_FIELS",        'Fájlok:');
define("LBL_FOLDER_WRITABLE",     'Írható?');
define("LBL_FOLDER_READABLE",     'Olvasható?');
define('LBL_FOLDER_ROOT',         'Gyökér mappa');

//Preview
define("LBL_PREVIEW",       'Előnézet');
define('LBL_CLICK_PREVIEW', 'Kattints ide az előnézethez.');

//Buttons
define('LBL_BTN_SELECT',         'Kiválasztás');
define('LBL_BTN_CANCEL',         'Mégsem');
define("LBL_BTN_UPLOAD",         'Feltöltés');
define('LBL_BTN_CREATE',         'Létrehozás');
define('LBL_BTN_CLOSE',          'Bezárás');
define("LBL_BTN_NEW_FOLDER",     'Új mappa');
define('LBL_BTN_NEW_FILE',       'Új fájl');
define('LBL_BTN_EDIT_IMAGE',     'Szerkesztés');
define('LBL_BTN_VIEW',           'Nézet');
define('LBL_BTN_VIEW_TEXT',      'Szöveg');
define('LBL_BTN_VIEW_DETAILS',   'Részletek');
define('LBL_BTN_VIEW_THUMBNAIL', 'Kisképek');
define('LBL_BTN_VIEW_OPTIONS',   'Nézet:');

//pagination
define('PAGINATION_NEXT',           'Következő');
define('PAGINATION_PREVIOUS',       'Előző');
define('PAGINATION_LAST',           'Utolsó');
define('PAGINATION_FIRST',          'Első');
define('PAGINATION_ITEMS_PER_PAGE', '%s adat mutatása oldalanként');
define('PAGINATION_GO_PARENT',      'Menj a szülő mappához');

//System
define('SYS_DISABLED', 'Hozzáférés megtagadva: A rendszer nem elérhető.');

//Cut
define('ERR_NOT_DOC_SELECTED_FOR_CUT', 'Nincs kiválasztva egyetlen dokumentum sem.');

//Copy
define('ERR_NOT_DOC_SELECTED_FOR_COPY', 'Nincs kiválasztva egyetlen dokumentum sem.');

//Paste
define('ERR_NOT_DOC_SELECTED_FOR_PASTE',  'Nincs kiválasztva egyetlen dokumentum sem.');
define('WARNING_CUT_PASTE',               'Biztos át szeretné mozgatni a kiválasztott dokumentumokat?');
define('WARNING_COPY_PASTE',              'Biztos át szeretné másolni a kiválasztott dokumentumokat?');
define('ERR_NOT_DEST_FOLDER_SPECIFIED',   'Nincs célmappa meghatározva.');
define('ERR_DEST_FOLDER_NOT_FOUND',       'Célmappa nem található.');
define('ERR_DEST_FOLDER_NOT_ALLOWED',     'Nincs joga átmozgatni a fájlokat ebbe a mappába');
define('ERR_UNABLE_TO_MOVE_TO_SAME_DEST', 'Fájl mozgatása sikertelen (%s): Forrásmappa és a célmappa megegyezik.');
define('ERR_UNABLE_TO_MOVE_NOT_FOUND',    'Fájl mozgatása sikertelen (%s): Kiválasztott fájl nem létezik.');
define('ERR_UNABLE_TO_MOVE_NOT_ALLOWED',  'Fájl mozgatása sikertelen (%s): Nem lehet hozzáférni a kiválasztott fájlhoz.');
define('ERR_NOT_FILES_PASTED',            'Nem lett egyetlen fájl sem beszúrva.');

//Search
define('LBL_SEARCH',             'Keresés');
define('LBL_SEARCH_NAME',        'Teljes/Rész fájlnév:');
define('LBL_SEARCH_FOLDER',      'Keresés helye:');
define('LBL_SEARCH_QUICK',       'Gyorskeresés');
define('LBL_SEARCH_MTIME',       'Fájl módosítás idő(intervallum):');
define('LBL_SEARCH_SIZE',        'Fájl méret:');
define('LBL_SEARCH_ADV_OPTIONS', 'Haladó beállítások');
define('LBL_SEARCH_FILE_TYPES',  'Fájl típus:');
define('SEARCH_TYPE_EXE',        'Alkalmazás');
define('SEARCH_TYPE_IMG',        'Kép');
define('SEARCH_TYPE_ARCHIVE',    'Arhív');
define('SEARCH_TYPE_HTML',       'HTML');
define('SEARCH_TYPE_VIDEO',      'Videó');
define('SEARCH_TYPE_MOVIE',      'Movie');
define('SEARCH_TYPE_MUSIC',      'Zene');
define('SEARCH_TYPE_FLASH',      'Flash');
define('SEARCH_TYPE_PPT',        'PowerPoint');
define('SEARCH_TYPE_DOC',        'Dokumentum');
define('SEARCH_TYPE_WORD',       'Word');
define('SEARCH_TYPE_PDF',        'PDF');
define('SEARCH_TYPE_EXCEL',      'Excel');
define('SEARCH_TYPE_TEXT',       'Text');
define('SEARCH_TYPE_UNKNOWN',    'Ismeretlen');
define('SEARCH_TYPE_XML',        'XML');
define('SEARCH_ALL_FILE_TYPES',  'Minden fájltípus');
define('LBL_SEARCH_RECURSIVELY', 'Rekurzív keresés:');
define('LBL_RECURSIVELY_YES',    'Igen');
define('LBL_RECURSIVELY_NO',     'Nem');
define('BTN_SEARCH',             'Keresés');

//thickbox
define('THICKBOX_NEXT',     'Következő&gt;');
define('THICKBOX_PREVIOUS', '&lt;Előző');
define('THICKBOX_CLOSE',    'Bezárás');

//Calendar
define('CALENDAR_CLOSE',    'Bezárás');
define('CALENDAR_CLEAR',    'Clear');
define('CALENDAR_PREVIOUS', '&lt;Előző');
define('CALENDAR_NEXT',     'Következő&gt;');
define('CALENDAR_CURRENT',  'Ma');
define('CALENDAR_MON',      'Hét');
define('CALENDAR_TUE',      'Ked');
define('CALENDAR_WED',      'Sze');
define('CALENDAR_THU',      'Csü');
define('CALENDAR_FRI',      'Pén');
define('CALENDAR_SAT',      'Szo');
define('CALENDAR_SUN',      'Vas');
define('CALENDAR_JAN',      'Jan');
define('CALENDAR_FEB',      'Feb');
define('CALENDAR_MAR',      'Már');
define('CALENDAR_APR',      'Ápr');
define('CALENDAR_MAY',      'Máj');
define('CALENDAR_JUN',      'Jún');
define('CALENDAR_JUL',      'Júl');
define('CALENDAR_AUG',      'Aug');
define('CALENDAR_SEP',      'Sze');
define('CALENDAR_OCT',      'Okt');
define('CALENDAR_NOV',      'Nov');
define('CALENDAR_DEC',      'Dec');

//ERROR MESSAGES
//deletion
define('ERR_NOT_FILE_SELECTED',       'Válasszon ki egy fájlt.');
define('ERR_NOT_DOC_SELECTED',        'Nincs kiválasztva egyetlen dokumentum sem.');
define('ERR_DELTED_FAILED',           'Nem lehet törölni a kiválasztott dokumentumokat.');
define('ERR_FOLDER_PATH_NOT_ALLOWED', 'A megadott elérési út tiltva.');

//class manager
define("ERR_FOLDER_NOT_FOUND", 'Nem létezik a megadott mappa: ');

//rename
define('ERR_RENAME_FORMAT',                 'Olyan nevet adjon meg, ami csak betűket, számokat, szóközt, kötőjelet vagy aláhúzást tartalmaz.');
define('ERR_RENAME_EXISTS',                 'Olyan nevet adjon meg, ami még nem létezik ebben a mappában.');
define('ERR_RENAME_FILE_NOT_EXISTS',        'A fájl/mappa nem létezik.');
define('ERR_RENAME_FAILED',                 'Az átnevezés nem lehetséges.');
define('ERR_RENAME_EMPTY',                  'Adjon meg egy nevet.');
define("ERR_NO_CHANGES_MADE",               'Nem történt módosítás.');
define('ERR_RENAME_FILE_TYPE_NOT_PERMITED', 'Nincs joga az adott kiterjesztésű fájl módosításához.');

//folder creation
define('ERR_FOLDER_FORMAT',          'Olyan nevet adjon meg, ami csak betűket, számokat, szóközt, kötőjelet vagy aláhúzást tartalmaz.');
define('ERR_FOLDER_EXISTS',          'Olyan nevet adjon meg, ami még nem létezik ebben a mappában.');
define('ERR_FOLDER_CREATION_FAILED', 'A mappa létrehozása nem lehetséges.');
define('ERR_FOLDER_NAME_EMPTY',      'Adjon meg egy nevet.');
define('FOLDER_FORM_TITLE',          'Új mappa');
define('FOLDER_LBL_TITLE',           'Mappa neve:');
define('FOLDER_LBL_CREATE',          'Mappa létrehozása');

//New File
define('NEW_FILE_FORM_TITLE', 'Új fájl');
define('NEW_FILE_LBL_TITLE',  'Fájl neve:');
define('NEW_FILE_CREATE',     'Fájl létrehozása');

//file upload
define("ERR_FILE_NAME_FORMAT",      'Olyan nevet adjon meg, ami csak betűket, számokat, szóközt, kötőjelet vagy aláhúzást tartalmaz.');
define('ERR_FILE_NOT_UPLOADED',     'Nem választott ki egyetlen fájlt sem.');
define('ERR_FILE_TYPE_NOT_ALLOWED', 'Nincs joga az adott fájltípus feltöltéséhez.');
define('ERR_FILE_MOVE_FAILED',      'Hiba a fájl feltöltése közben.');
define('ERR_FILE_NOT_AVAILABLE',    'A fájl nem elérhető.');
define('ERROR_FILE_TOO_BID',        'Fájl túl nagy. (maximum: %s)');
define('FILE_FORM_TITLE',           'Fájl feltöltés');
define('FILE_LABEL_SELECT',         'Fájl kiválasztása');
define('FILE_LBL_MORE',             'Több fájl feltöltése');
define('FILE_CANCEL_UPLOAD',        'Fájl feltöltés megszakítása');
define('FILE_LBL_UPLOAD',           'Feltöltés');

//file download
define('ERR_DOWNLOAD_FILE_NOT_FOUND', 'Nem választott ki egyetlen fájlt sem.');

//Rename
define('RENAME_FORM_TITLE', 'Átnevezés');
define('RENAME_NEW_NAME',   'Új név');
define('RENAME_LBL_RENAME', 'Átnevezés');

//Tips
define('TIP_FOLDER_GO_DOWN', 'Egy kattintással a mappába...');
define("TIP_DOC_RENAME",     'Dupla kattintás a szerkesztéshez...');
define('TIP_FOLDER_GO_UP',   'Egy kattintással a szülő mappába...');
define("TIP_SELECT_ALL",     'Mind kijelöl');
define("TIP_UNSELECT_ALL",   'Kijelölés megszűntetése');

//WARNING
define('WARNING_DELETE',        'Valóban törölni szeretné a kijelölt fájlokat?');
define('WARNING_IMAGE_EDIT',    'Válasszon ki egy képet a szerkesztéshez.');
define('WARNING_NOT_FILE_EDIT', 'Válasszon ki egy fájlt a szerkesztéshez.');
define('WARING_WINDOW_CLOSE',   'Valóban be szeretné zárni az ablakot?');

//Preview
define('PREVIEW_NOT_PREVIEW',       'Előnézet nem elérhető.');
define('PREVIEW_OPEN_FAILED',       'Fájl megnyitása sikertelen.');
define('PREVIEW_IMAGE_LOAD_FAILED', 'Kép betöltése sikertelen.');

//Login
define('LOGIN_PAGE_TITLE', 'Ajax File Manager Belépés');
define('LOGIN_FORM_TITLE', 'Belépés');
define('LOGIN_USERNAME',   'Felhasználói név:');
define('LOGIN_PASSWORD',   'Jelszó:');
define('LOGIN_FAILED',     'Hibás felhasználói név/jelszó.');

//Below for Image Editor
//Warning 
define('IMG_WARNING_NO_CHANGE_BEFORE_SAVE', "Nem végzett semmi módosítást a képen.");

//General
define('IMG_GEN_IMG_NOT_EXISTS',    'Kép nem létezik.');
define('IMG_WARNING_LOST_CHANAGES', 'Minden nem mentett módosítás el fog veszni. Valóban folytatni szeretné?');
define('IMG_WARNING_REST',          'Minden nem mentett módosítás el fog veszni. Valóban vissza szeretne térni az eredeti képhez?');
define('IMG_WARNING_EMPTY_RESET',   'Nem történt semmi módosítás a képen.');
define('IMG_WARING_WIN_CLOSE',      'Be szeretné zárni az ablakot?');
define('IMG_WARNING_UNDO',          'Valóban az előző állapotot szeretné visszaállítani?');
define('IMG_WARING_FLIP_H',         'Valóban vízszintesen szeretné tükrözni?');
define('IMG_WARING_FLIP_V',         'Valóban függőlegesen szeretné tükrözni?');
define('IMG_INFO',                  'Kép információk');

//Mode
define('IMG_MODE_RESIZE', 'Átméretezés:');
define('IMG_MODE_CROP',   'Vágás:');
define('IMG_MODE_ROTATE', 'Forgatás:');
define('IMG_MODE_FLIP',   'Tükrözés:');

//Button
define('IMG_BTN_ROTATE_LEFT',  '90&deg; Balra');
define('IMG_BTN_ROTATE_RIGHT', '90&deg; Jobbra');
define('IMG_BTN_FLIP_H',       'Vízszintes tükrözés');
define('IMG_BTN_FLIP_V',       'Függőleges tükrözés');
define('IMG_BTN_RESET',        'Eredeti állapot');
define('IMG_BTN_UNDO',         'Visszavonás');
define('IMG_BTN_SAVE',         'Mentés');
define('IMG_BTN_CLOSE',        'Bezárás');
define('IMG_BTN_SAVE_AS',      'Mentés másként');
define('IMG_BTN_CANCEL',       'Mégsem');

//Checkbox
define('IMG_CHECKBOX_CONSTRAINT', 'Arány megtartása?');

//Label
define('IMG_LBL_WIDTH',       'Szélesség:');
define('IMG_LBL_HEIGHT',      'Magasság:');
define('IMG_LBL_X',           'X:');
define('IMG_LBL_Y',           'Y:');
define('IMG_LBL_RATIO',       'Arány:');
define('IMG_LBL_ANGLE',       'Szög:');
define('IMG_LBL_NEW_NAME',    'Új név:');
define('IMG_LBL_SAVE_AS',     'Mentés másként');
define('IMG_LBL_SAVE_TO',     'Mentés:');
define('IMG_LBL_ROOT_FOLDER', 'Gyökér mappa');

//Editor
//Save as 
define('IMG_NEW_NAME_COMMENTS',           'A fájl kiterjesztését nem kell megadni.');
define('IMG_SAVE_AS_ERR_NAME_INVALID',    'Olyan nevet adjon meg, ami csak betűket, számokat, szóközt, kötőjelet vagy aláhúzást tartalmaz.');
define('IMG_SAVE_AS_NOT_FOLDER_SELECTED', 'Nincs célmappa kiválasztva.');	
define('IMG_SAVE_AS_FOLDER_NOT_FOUND',    'A célmappa nem létezik.');
define('IMG_SAVE_AS_NEW_IMAGE_EXISTS',    'Ilyen nevű kép már létezik.');

//Save
define('IMG_SAVE_EMPTY_PATH',              'Üres elérési út.');
define('IMG_SAVE_NOT_EXISTS',              'A kép nem létezik.');
define('IMG_SAVE_PATH_DISALLOWED',         'Hozzáférés a fájlhoz sikertelen.');
define('IMG_SAVE_UNKNOWN_MODE',            'Ismeretlen képszerkesztési művelet');
define('IMG_SAVE_RESIZE_FAILED',           'Hiba a kép átméretezése közben.');
define('IMG_SAVE_CROP_FAILED',             'Hiba a kép vágása közben.');
define('IMG_SAVE_FAILED',                  'Kép mentése sikertelen.');
define('IMG_SAVE_BACKUP_FAILED',           'Unable to backup the original image.');
define('IMG_SAVE_ROTATE_FAILED',           'Hiba a kép forgatása közben.');
define('IMG_SAVE_FLIP_FAILED',             'Hiba a kép tükrözése közben.');
define('IMG_SAVE_SESSION_IMG_OPEN_FAILED', 'Kép megnyitása munkamentből sikertelen.');
define('IMG_SAVE_IMG_OPEN_FAILED',         'Kép megnyitása sikertelen.');

//UNDO
define('IMG_UNDO_NO_HISTORY_AVAIALBE', 'Az előzmények üres.');
define('IMG_UNDO_COPY_FAILED',         'A kép visszaállítása sikeretelen.');
define('IMG_UNDO_DEL_FAILED',          'A kép törlése sikertelen.');

//Above for Image Editor
//Session
define("SESSION_PERSONAL_DIR_NOT_FOUND",     'Nem sikerült a session mappát megynitni.');
define("SESSION_COUNTER_FILE_CREATE_FAILED", 'A session fájl megnyitása sikertelen.');
define('SESSION_COUNTER_FILE_WRITE_FAILED',  'A session fájl írása sikertelen.');

//Below for Text Editor
define('TXT_FILE_NOT_FOUND',           'Fájl nem található.');
define('TXT_EXT_NOT_SELECTED',         'Válasszon egy fájl kiterjesztést.');
define('TXT_DEST_FOLDER_NOT_SELECTED', 'Válasszon egy célmappát.');
define('TXT_UNKNOWN_REQUEST',          'Ismeretlen kérés.');
define('TXT_DISALLOWED_EXT',           'Nincs joga a fájl szerkesztéséhez/hozzáadásához.');
define('TXT_FILE_EXIST',               'Fájl már létezik.');
define('TXT_FILE_NOT_EXIST',           'Fájl nem létezik.');
define('TXT_CREATE_FAILED',            'Hiba a fájl létrehozásakor.');
define('TXT_CONTENT_WRITE_FAILED',     'Hiba a fájl írásakor.');
define('TXT_FILE_OPEN_FAILED',         'Hiba a fájl megnyitásakor.');
define('TXT_CONTENT_UPDATE_FAILED',    'Hiba a fájl frissítésekor.');
define('TXT_SAVE_AS_ERR_NAME_INVALID', 'Olyan nevet adjon meg, ami csak betűket, számokat, szóközt, kötőjelet vagy aláhúzást tartalmaz.');

?>
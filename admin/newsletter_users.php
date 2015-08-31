<?php

// Közvetlenül ezt az állományt kérte
if (!eregi("admin.php", $_SERVER['SCRIPT_NAME'])) {
    die ("Közvetlenül nem lehet az állományhoz hozzáférni...");
}

//breadcrumb
$breadcrumb->add($locale->get('title_users'), 'admin.php?p='.$module_name.'&act=users');

/**
 * Ha valamilyen mûveletet hajtunk végre
 */
if ($sub_act == "add" || $sub_act == "mod") {
    $titles = array('add' => $locale->get('users_title_add'), 'mod' => $locale->get('users_title_mod'));

    //szukseges fuggvenykonyvtarak betoltese
    require_once 'HTML/QuickForm.php';
    require_once 'HTML/QuickForm/Renderer/ArraySmarty.php';
    require_once $include_dir.'/function.newsletter.php';

    //elinditjuk a form-ot
    $form =& new HTML_QuickForm('frm_users', 'post', 'admin.php?p='.$module_name);

    //a szukseges szoveget jelzo resz beallitasa
    $form->setRequiredNote($locale->get('users_form_required_note'));

    $form->addElement('header', 'users',   $locale->get('title_users'));
    $form->addElement('hidden', 'act',     $page);
    $form->addElement('hidden', 'sub_act', $sub_act);

    //felhasznalo neve
    $form->addElement('text', 'name', $locale->get('users_field_username'));

    //felhasznalo e-mail cime
    $form->addElement('text', 'email', $locale->get('users_field_email'));

    //lekerdezzuk, hogy milyen csoportokhoz lehet hozzaadni a user-t
    $query = "
        SELECT g.newsletter_group_id AS gid, g.group_name AS gname
        FROM iShark_Newsletter_Groups g
        WHERE g.is_deleted = '0'
        ORDER BY g.group_name
    ";
    $result =& $mdb2->query($query);
    $select =& $form->addElement('select', 'group', $locale->get('users_field_groups'), $result->fetchAll('', $rekey = true));
    $select->setSize(5);
    $select->setMultiple(true);

    //szurok beallitasa
    $form->applyFilter('__ALL__', 'trim');

    //szabalyok beallitasa
    $form->addRule('name',  $locale->get('users_error_required_name'),  'required');
    $form->addRule('email', $locale->get('users_error_required_email'), 'required');
    $form->addRule('email', $locale->get('users_error_invalid_email'),  'email');

    /**
     * ha uj felhasznalot adunk hozza
     */
    if ($sub_act == "add") {
        //szabalyok hozzadasa - csak hozzaadasnal
        $form->addFormRule('check_addNewsletterUser');

        //ellenorzes, vegso muveletek
        if ($form->validate()) {
            // SQL Escape karakterkitöltés
            $form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

            $name  = $form->getSubmitValue('name');
            $email = $form->getSubmitValue('email');
            $group = $form->getSubmitValue('group');

            //letrehozzuk a felhasznalot
            $newsletter_user_id = $mdb2->extended->getBeforeID('iShark_Newsletter_Users', 'newsletter_user_id', TRUE, TRUE);
            $query = "
                INSERT INTO iShark_Newsletter_Users
                (newsletter_user_id, name, email, is_active, is_deleted)
                VALUES
                ($newsletter_user_id, '$name', '$email', '1', '0')
            ";
            $mdb2->exec($query);

            //lekerdezzuk az utolsonak felvitt user azonositojat
            $last_user_id = $mdb2->extended->getAfterID($newsletter_user_id, 'iShark_Newsletter_Users', 'newsletter_user_id');

            //hozzaadjuk a kivalasztott csoport(ok)hoz
            if (is_array($group)) {
                foreach ($group as $group_id) {
                    $query = "
                        INSERT INTO iShark_Newsletter_Groups_Users
                        (newsletter_user_id, newsletter_group_id)
                        VALUES
                        ($last_user_id, $group_id)
                    ";
                    $mdb2->exec($query);
                }
            }

            //"fagyasztjuk" a form-ot
            $form->freeze();

            logger($page.'_'.$sub_act);

            //visszadobjuk a lista oldalra
            header('Location: admin.php?p='.$module_name.'&act='.$page);
            exit;
        }
    } //felhasznalo hozzaadas vege

    /**
     * ha modositunk egy felhasznalot
     */
    if ($sub_act == "mod") {
        $uid = intval($_REQUEST['uid']);

        //form-hoz elemek hozzaadasa - csak modositasnal
        $form->addElement('hidden', 'uid', $uid);

        //lekerdezzuk a user tablat, es az eredmenyeket beallitjuk alapertelmezettnek
        $query = "
            SELECT *
            FROM iShark_Newsletter_Users
            WHERE newsletter_user_id = $uid
        ";
        $result = $mdb2->query($query);

        if ($result->numRows() == 0) {
            header('Location: admin.php?p='.$module_name.'&act='.$page);
            exit;
        }

        while ($row = $result->fetchRow()) {
            //beallitjuk az alapertelmezett ertekeket - csak modositasnal
            $form->setDefaults(array(
                'name'  => $row['name'],
                'email' => $row['email'],
                )
            );
        }

        //lekerdezzuk a csoportuser tablat, es beallitjuk alapertelmezettnek
        $query = "
            SELECT *
            FROM iShark_Newsletter_Groups_Users
            WHERE newsletter_user_id = $uid
        ";
        $result =& $mdb2->query($query);
        $select->setSelected($result->fetchCol());

        //szabalyok hozzadasa - csak modositasnal
        $form->addFormRule('check_modNewsletterUser');

        //ellenorzes, vegso muveletek
        if ($form->validate()) {
            // SQL escape karakterek hozzáadása
            $form->applyFilter('__ALL__', array(&$mdb2, 'escape'));

            $name  = $form->getSubmitValue('name');
            $email = $form->getSubmitValue('email');
            $group = $form->getSubmitValue('group');

            $query = "
                UPDATE iShark_Newsletter_Users
                SET name  = '$name',
                    email = '$email'
                WHERE newsletter_user_id = $uid
            ";

            $mdb2->exec($query);

            //kitoroljuk a jelenlegi csoporttagsagait a felhasznalonak
            $query = "
                DELETE FROM iShark_Newsletter_Groups_Users
                WHERE newsletter_user_id = $uid
            ";
            $mdb2->exec($query);

            //hozzaadjuk a kivalasztott csoport(ok)hoz
            if (is_array($group)) {
                foreach ($group as $group_id) {
                    $query = "
                        INSERT INTO iShark_Newsletter_Groups_Users
                        (newsletter_user_id, newsletter_group_id)
                        VALUES
                        ($uid, $group_id)
                    ";
                    $mdb2->exec($query);
                }
            }

            //"fagyasztjuk" a form-ot
            $form->freeze();

            logger($page.'_'.$sub_act);

            //visszadobjuk a lista oldalra
            header('Location: admin.php?p='.$module_name.'&act='.$page);
            exit;
        }
    } //modositas vege

    $form->addElement('submit', 'submit', $locale->get('users_form_submit'), 'class="submit"');
    $form->addElement('reset',  'reset',  $locale->get('users_form_reset'),  'class="reset"');

    $renderer =& new HTML_QuickForm_Renderer_Array(true, true);
    $form->accept($renderer);

    //breadcrumb
    $breadcrumb->add($titles[$sub_act], '#');

    $tpl->assign('form',       $renderer->toArray());
    $tpl->assign('lang_title', $titles[$sub_act]);

    // capture the array stucture
    ob_start();
    print_r($renderer->toArray());
    $tpl->assign('dynamic_array', ob_get_contents());
    ob_end_clean();

    //megadjuk a tpl file nevet, amit atadunk az admin.php-nek
    $acttpl = 'dynamic_form';
}

/**
 * ha torlunk egy felhasznalot
 */
if ($sub_act == "del") {
    $uid = intval($_GET['uid']);

    // Felhasználó törlése
    $query = "
        UPDATE iShark_Newsletter_Users
        SET is_deleted = '1'
        WHERE newsletter_user_id = $uid
    ";
    $mdb2->exec($query);

    logger($page.'_'.$sub_act);

    header('Location: admin.php?p='.$module_name.'&act='.$page);
    exit;
} //torles vege

// Felhasználó aktiválása, deaktiválása
if ($sub_act == 'act') {
    $uid = intval($_REQUEST['uid']);

    $query = "
        UPDATE iShark_Newsletter_Users
        SET is_active = (CASE is_active WHEN '1' THEN '0' ELSE '1' END)
        WHERE newsletter_user_id = $uid
    ";
    $mdb2->exec($query);

    logger($page.'_'.$sub_act);

    header('Location: admin.php?p='.$module_name.'&act='.$page);
    exit;
}

/**
 * Ha a listát mutatjuk
 */
if ($sub_act == "lst") {
//rendezes
    $fieldselect1 = "";
    $fieldselect2 = "";
    $ordselect1   = "";
    $ordselect2   = "";
    if (isset($_REQUEST['field']) && is_numeric($_REQUEST['field']) && isset($_REQUEST['ord']) && ($_REQUEST['ord'] == "asc" || $_REQUEST['ord'] == "desc")) {
        $field      = intval($_REQUEST['field']);
        $ord        = $_REQUEST['ord'];
        $fieldorder = " ORDER BY";

        switch ($field) {
            case 1:
                $fieldorder   .= " name ";
                $fieldselect1 = "selected";
                break;
            case 2:
                $fieldorder   .= " email ";
                $fieldselect2 = "selected";
                break;
        }

        switch ($ord) {
            case "asc":
                $order      = "ASC";
                $ordselect1 = "selected";
                break;
            case "desc":
                $order      = "DESC";
                $ordselect2 = "selected";
                break;
        }
    } else {
        $field        = "";
        $ord          = "";
        $fieldorder   = "ORDER BY name";
        $fieldselect4 = "selected";
        $order        = "ASC";
    }

    $query = "
        SELECT nu.newsletter_user_id as uid, name, email, is_active
        FROM iShark_Newsletter_Users nu
        WHERE is_deleted = '0'
        $fieldorder $order
    ";

    // Lapozó
    require_once 'Pager/Pager.php';
    $paged_data = Pager_Wrapper_MDB2($mdb2, $query, $pagerOptions);

    // Csoportok lekérdezése
    foreach ($paged_data['data'] as $key => $adat) {
        $grouplist = "";
        $query = "
            SELECT g.group_name AS gname
            FROM iShark_Newsletter_Groups g, iShark_Newsletter_Groups_Users gu
            WHERE gu.newsletter_group_id = g.newsletter_group_id AND gu.newsletter_user_id = ".$adat['uid']." 
                AND g.is_deleted = '0'
        ";
        $result =& $mdb2->query($query);
        while ($row = $result->fetchRow())
        {
            $grouplist .= $row['gname']."<br />";
        }
        $paged_data['data'][$key]['grouplist'] = $grouplist;
    }

    $tpl->assign('page_list',  $paged_data['links']);
    $tpl->assign('page_data',  $paged_data['data']);


    $add_new = array(
        array(
            'link'  => 'admin.php?p='.$module_name.'&amp;act='.$page.'&amp;sub_act=add',
            'title' => $locale->get('act_user_add'),
            'pic'   => 'add.jpg'
        )
    );

    $tpl->assign('add_new',      $add_new);
    $tpl->assign('fieldselect1', $fieldselect1);
    $tpl->assign('fieldselect2', $fieldselect2);
    $tpl->assign('ordselect1',   $ordselect1);
    $tpl->assign('ordselect2',   $ordselect2);

    $acttpl = 'newsletter_userlist';
}

?>
<?php
    $xmlstr = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<options>
</options>
XML;

    /**
     * Export config action
     */
    if ('export' == $_REQUEST['action'])
    {
        $sxe = new SimpleXMLElement($xmlstr);
        foreach ($options as $value)
        {
            if ($value['id'] != '')
            {
                $option = $sxe->addChild('option');
                $option->addChild('id', $value['id']);
                $option->addChild('value', get_option($value['id']));
            }
        }
        for ($i = 1; $i < 4; $i++)
        {
            $option = $sxe->addChild('option');
            $option->addChild('id', 'filter_' . $i);
            $option->addChild('value', get_option('filter_' . $i));
        }
        header('Content-Type: application/xml');
        header('Content-Disposition: attachment;filename="settings.xml"');
        echo $sxe->asXML();
        die;
    }
    /**
     * Import config action
     */
    elseif ('import' == $_REQUEST['action'] && $_FILES["file"])
    {
        $xml = simplexml_load_file($_FILES["file"]["tmp_name"]);
        foreach ($xml->children() as $child)
        {
            $id = (string) $child->id;
            $value = (string) $child->value;

            if (!get_option($id))
            {
                add_option($id, $value, '', 'YES');
            }
            elseif (get_option($id) != $value)
            {
                update_option($id, $value);
            }
        }
        header("Location: admin.php?page=functions.php&export=true");
        die;
    }
    /**
     * Save config action
     */
    elseif ('save' == $_REQUEST['action'])
    {
        foreach ($options as $value)
        {
            update_option($value['id'], $_REQUEST[$value['id']]);

            if ($value['type'] == 'fieldset')
            {
                foreach ($value['fields'] as $field)
                {
                    update_option($field['id'], preg_replace('/\s/', '-', $_REQUEST[$field['id']]));
                }
            }
        }
        foreach ($options as $value)
        {
            if (isset($_REQUEST[$value['id']]))
            {
                update_option($value['id'], $_REQUEST[$value['id']]);
            }
            else
            {
                delete_option($value['id']);
            }
        }
        header("Location: admin.php?page=functions.php&saved=true");
        die;
    }
    /**
     * Reset config action
     */
    elseif ('reset' == $_REQUEST['action'])
    {
        foreach ($options as $value)
        {
            delete_option($value['id']);
        }
        header("Location: admin.php?page=functions.php&reset=true");
        die;
    }
?>

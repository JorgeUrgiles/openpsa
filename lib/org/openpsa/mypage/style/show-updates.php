<?php
$formatter = $data['l10n']->get_formatter();
foreach (['today', 'yesterday'] as $type) {
    if (empty($data[$type])) {
        continue;
    }
    echo "<div class=\"area\">\n";
    echo "<h2>" . $data['l10n']->get("updated " . $type) . "</h2>\n";
    echo "<ul class=\"updated\">\n";
    foreach ($data[$type] as $document) {
        $class = explode('.', $document->component);
        $class = $class[count($class) - 1];

        $onclick = '';
        if ($class == 'calendar') {
            $url = "#";
            $onclick = " onclick=\"javascript:window.open('{$document->document_url}', 'event', 'toolbar=0,location=0,status=0,height=600,width=300,resizable=1');\"";
        } else {
            $url = $document->document_url;
        }

        try {
            if ($document->editor) {
                $editor = new midcom_db_person($document->editor);
            } else {
                $editor = new midcom_db_person($document->creator);
            }
            $contact = new org_openpsa_widgets_contact($editor);
            echo "<li class=\"updated-{$class}\"><a href=\"{$url}\"{$onclick}>{$document->title}</a> <div class=\"metadata\">" . $formatter->datetime($document->edited) . " (" . $contact->show_inline() . ")</div></li>\n";
        } catch (midcom_error $e) {
        }
    }
    echo "</ul></div>\n";
}

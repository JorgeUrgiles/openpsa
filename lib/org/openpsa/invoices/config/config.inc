'schemadb'             => 'file:/org/openpsa/invoices/config/schemadb_default.php',
'schemadb_config'      => 'file:/org/openpsa/invoices/config/schemadb_config.php',
'schemadb_billing_data' => 'file:/org/openpsa/invoices/config/schemadb_default_billing_data.php',
'schemadb_send_mail'  => 'file:/org/openpsa/invoices/config/schemadb_send_mail.php',
'default_hourly_price' => 0,
'default_due_days'     => 14,
'vat_percentages'      => '0,17,22',
'invoice_number_format' => '#%06d',
//contains the class file to create the pdf for the invoice, if set to false pdf-creation is disabled
'invoice_pdfbuilder_class' => false, //implements org_openpsa_invoices_interfaces_pdfbuilder
'invoice_pdfbuilder_reminder_class' => false,

'invoice_mail_bcc'       => false,
'invoice_mail_from_address' => 'foo@example.org',

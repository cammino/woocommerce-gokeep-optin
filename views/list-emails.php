<style>
.csv_optin { display: none; }
.csv_optin textarea { width: 100%; height: 230px; }
</style>

<div class="wrap">
  <h1>Subscribers</h1>
  
  <div class="tablenav top">
    <div class="alignleft actions bulkactions">
      <input type="submit" id="show_subscriber_textarea" class="button action" value="Copiar E-mails">
    </div>
  </div>
  <div class="csv_optin">
    <p>Copie e cole no seu provedor de email.</p>
    <textarea readonly="true">
Nome,Email,Data Criação
<?php
foreach ($optins as $optin) {
  $date = new DateTime($optin->created_at);
  echo $optin->name . ',' . $optin->email . ',' . $date->format('d/m/Y H:i:s') . ";\n"; 
} 
?>
    </textarea>
  </div>

  <br class="clearfix">

  <table class="wp-list-table widefat fixed striped pages">
    <thead>
    <tr>
      <td scope="col" id="title" class="manage-column column-title column-name">Nome</td>
      <td scope="col" id="title" class="manage-column column-email">E-mail</td>
      <td scope="col" id="title" class="manage-column column-createdat">Data Criação</td>
    </tr>
    </thead>
    <tbody id="the-list">
      <?php foreach ($optins as $optin): ?>
        <tr>
          <td class="name column-name"><?php echo $optin->name; ?></td>
          <td class="email column-email"><a href="mailto:<?php echo $optin->email; ?>"><?php echo $optin->email; ?></a></td>
          
          <?php $date = new DateTime($optin->created_at); ?>
          <td class="email column-createdat"><?php echo $date->format('d/m/Y H:i:s'); ?></td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>


<script>
  jQuery(document).ready(function(){
    jQuery('#show_subscriber_textarea').on('click', function(evt){
      evt.preventDefault();
      jQuery('.csv_optin').slideToggle();
    });
  });
</script>
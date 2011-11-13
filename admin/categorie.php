<?php
/**
 * Edit Tags Administration Screen.
 *
 * @package WordPress
 * @subpackage Administration
 */

/** WordPress Administration Bootstrap */
/*require_once('./admin.php');
$tax = get_taxonomy( $taxnow );
if ( !current_user_can( $tax->cap->manage_terms ) )
	wp_die( __( 'Cheatin&#8217; uh?' ) );
*/



$messages[1] = __('Item added.');
$messages[2] = __('Item deleted.');
$messages[3] = __('Item updated.');
$messages[4] = __('Item not added.');
$messages[5] = __('Item not updated.');
$messages[6] = __('Item not deleted.');
$messages[7] = __('Impossibile cancellare Categorie che contengono Categorie Figlio. Cancellare prima i Figli');
$messages[8] = __('Impossibile cancellare Categorie che sono collegate ad Atti');

?>
<div class="wrap nosubsub">
<img src="<?php echo WP_CONTENT_URL; ?>/plugins/AlboPretorio/img/categoria32.png" alt="Icona Visualizza Categorie Atti" style="display:inline;float:left;margin-top:10px;"/>
<h2 style="margin-left:40px;">Categorie Atti</h2>

<?php 
if ( isset($_REQUEST['message']) && ( $msg = (int) $_REQUEST['message'] ) ) {
	echo '<div id="message" class="updated"><p>'.$messages[$msg].'</p></div>';
	$_SERVER['REQUEST_URI'] = remove_query_arg(array('message'), $_SERVER['REQUEST_URI']);
}
if ($_REQUEST['action']=="edit"){
	$risultato=ap_get_categoria($_REQUEST['id']);
//	print_r($risultato);
	$edit=True;
}else{
	$edit=False;
}
?>
<br class="clear" />
<div id="col-container">
<div id="col-right">
<div class="col-wrap">
<h3>Elenco Categorie gi&agrave; codificate</h3>
<table class="widefat" id="elenco-categorie"> 
    <thead>
    	<tr>
        	<th scope="col" style="text-align:center;">Categorie</th>
		</tr>
    </thead>
    <tbody id="the-list">
<?php $lista=ap_get_categorie_gerarchica(); 
echo '<tr>
        	<td>
			<ul>';
if ($lista){
	foreach($lista as $riga){
	 $shift=(((int)$riga[2])*30)+5;
		echo'<li style="text-align:left;padding-left:'.$shift.'px;">
			<a href="?page=categorie&amp;action=delete-categorie&amp;id='.$riga[0].'" rel="'.$riga[1].'" class="dc">
			<img src="'.WP_CONTENT_URL.'/plugins/AlboPretorio/img/cross.png" alt="Delete" title="Delete" />
			</a>
			<a href="?page=categorie&amp;action=edit-categorie&amp;id='.$riga[0].'" rel="'.$riga[1].'">
			<img src="'.WP_CONTENT_URL.'/plugins/AlboPretorio/img/edit.png" alt="Edit" title="Edit" />
			</a>
			('.$riga[0] .') '.$riga[1] .'
			</li>'; 
	}
} else {
		echo '<li>Nessuna Categoria Codificata</li>';
}
echo '</td>
	</tr>
</ul>';
?>
            </tbody>
        </table>
</div>
</div><!-- /col-right -->

<div id="col-left">
<div class="form-wrap">
<h3><?php echo $tax->labels->add_new_item; ?></h3>
<form id="addtag" method="post" action="?page=categorie" class="<?php if($edit) echo "edit"; else echo "validate"; ?>"  >
<input type="hidden" name="action" value="<?php if($edit) echo "memo-categoria"; else echo "add-categorie"; ?>"/>
<input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>" />
<?php wp_nonce_field('add-tag', '_wpnonce_add-tag'); ?>

<div class="form-field form-required">
	<label for="tag-name">Nome</label>
	<input name="cat-name" id="cat-name" type="text" value="<?php if($edit) echo stripslashes($risultato[0]->Nome); ?>" size="40" aria-required="true" />
	<p>Nome della categoria.</p>
</div>
<div class="form-field">
	<label for="parent">Parente di:</label>
	<?php 
	if($edit){
		echo ap_get_dropdown_categorie('cat-parente','cat-parente','','',$risultato[0]->Genitore);
	}else{
		echo ap_get_dropdown_categorie('cat-parente','cat-parente','postform','',0); 
	}
	?>
	<p>Se si sta creando una sottocategoria, selezionare il genitore. Questo sistema permette di creare una struttura gerarchica di categorie.</p>
</div>
<div class="form-field">
	<label for="tag-description">Descrizione</label>
	<textarea name="cat-descrizione" id="cat-descrizione" rows="5" cols="40"><?php if($edit) echo stripslashes($risultato[0]->Descrizione); ?></textarea>
	<p>Breve descrizione della categoria</p>
</div>
<div class="form-field  form-required">
	<label for="tag-durata">Durata</label>
	<input name="cat-durata" id="cat-durata" type="text" value="<?php if($edit) echo $risultato[0]->Giorni; else echo "0"; ?>" size="4" aria-required="true" />
	<p>Durata di default della validit&agrave; degli atti di questa categoria</p>
</div>

<?php
if($edit) {
	echo '<input type="submit" name="memo" id="memo" class="button" value="Memorizza Modifiche Categoria '.$risultato[0]->Nome.'" rel="'.stripslashes($risultato[0]->Nome).'" />';
}else{
	echo '<input type="submit" name="submit" id="submit" class="button" value="Aggiungi nuova Categoria"  />';	
}
?>
</form>
</div>
</div><!-- /col-container -->
</div><!-- /wrap -->


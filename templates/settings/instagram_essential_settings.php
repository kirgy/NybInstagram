<?php



?>

<div class="nybInstagramSettings-wrap">

      <h1>
         NybInstagram Configuration Stage 1 of 3 (essential settings)
      </h1>
      <?php if(count($aMessages)>0) { ?>
         <div id="message" class="messages updated">
            <p>
               <?php foreach ($aMessages as $sValue) { ?>
                  <?php echo $sValue ?>
               <?php } ?>
            </p>
         </div>
      <?php } ?>
      <?php if(count($aErrors)>0) { ?>
         <div id="errors" class="messages error">
            <p>
               <?php foreach ($aErrors as $sValue) { ?>
                  <?php echo $sValue ?>
               <?php } ?>
            </p>
         </div>
      <?php } ?>


      <p>
         Please use the following form to enter API settings for the website's instagram feed. First, please create an app on the Instagram developer site by following these instructions.
      </p>
      <ol>
      	<li>
      		Register as a developer at <a href="http://instagram.com/developer/" target="_blank">instagram.com/developer</a>
      	</li>
      	<li>
      		Select the 'Manage Clients' link in the menu. Then select the 'Register new client' button on the follow page. 
      	</li>
      	<li>
      		Enter the following information on the form:
      			<table class="widefat importers">
      				<tr>
      					<td class="import-system row-title">
      						Application name
      					</td>
      					<td>
      						<?php echo get_bloginfo( 'name' );?> NybbleMouse photo Feed
      					</td>
      				</tr>
      				<tr class="alternate">
      					<td class="import-system row-title">
      						Description
      					</td>
      					<td>
      						A simple and beautiful Instagram feed wordpress plugin.
      					</td>
      				</tr>
      				<tr>
      					<td class="import-system row-title">
      						OAuth redirect_uri
      					</td>
      					<td>
      						<?php echo $this->get_redirect_url()?>
      					</td>
      				</tr>
      				<tr class="alternate">
      					<td class="import-system row-title">
      						Disable implicit OAuth
      					</td>
      					<td>
      						Ticked
      					</td>
      				</tr>
      				<tr>
      					<td class="import-system row-title">
      						Enforce signed header
      					</td>
      					<td>
      						Unticked
      					</td>
      				</tr>
      			</table>
      	</li>
      	<li>
      		Save the configuration.
      	</li>
      	<li>
      		Enter the given client id and client secret in the form below.
      	</li>
      </ol>
         <div class="nybSetting">       
            <h2>
               API &amp; Account Settings
            </h2>
         </div>      
      <form action="" method="post">
      	<input type="hidden" name="action" value="essential"/>
         <div class="nybSetting">
            <table class="form-table">
               <tr>
                  <th>   
                     <label>
                        Instagram Client ID
                     </label>
                  </th>
                  <td>
                     <input type="text" name="nybinstagram_client_id" class="regular-text code" value="<?php echo (get_option( 'nybinstagram_client_id')=='null') ? '' : get_option( 'nybinstagram_client_id'); ?>">
                  </td>
               </tr>
            </table>            
         </div>
         <div class="nybSetting">
            <table class="form-table">
               <tr>
                  <th>   
                     <label>
                        Instagram Client Secret
                     </label>
                  </th>
                  <td>
                     <input type="text" name="nybinstagram_client_secret" class="regular-text code" value="<?php echo (get_option( 'nybinstagram_client_secret')=='null') ? '' : get_option( 'nybinstagram_client_secret'); ?>">
                  </td>
               </tr>
            </table>            
         </div>
         <div class="nybSetting">
            <input type="submit" value="Save Settings" class="button button-primary"/>
         </div>
      </form>

</div>
      
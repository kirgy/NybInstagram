<?php

?>
<div class="nybInstagramSettings-wrap">

      <h1>
         NybInstagram Configuration Stage 3 of 3 (general settings)
      </h1>
      <?php if(count($this->aMessages)>0) { ?>
         <div id="message" class="messages updated">
            <p>
               <?php foreach ($this->aMessages as $sValue) { ?>
                  <?php echo $sValue ?>
               <?php } ?>
            </p>
         </div>
      <?php } ?>
      <?php if(count($this->aErrors)>0) { ?>
         <div id="errors" class="messages error">
            <p>
               <?php foreach ($this->aErrors as $sValue) { ?>
                  <?php echo $sValue ?>
               <?php } ?>
            </p>
         </div>
      <?php } ?>


    <div class="nybSetting">       
        <h2>
            General Settings
        </h2>
        <p>
            NybInstagram is now fully authenticated. Use the options below to follow  given hashtag or user account. The given user account must not be set to private.
        </p>
    </div>
      <form action="" method="post">
       <div class="nybSetting">
            <table class="form-table">
               <tr>
                  <th>         
                     <label>
                        Account User Name
                     </label>
                  </th>
                  <td>
                     <input type="text" name="user-account" class="regular-text code" value="<?php echo get_option( 'nybinstagram_account_name', '' ); ?>">
                  </td>
               </tr>
            </table>
         </div>

         <div class="nybSetting">
            <table class="form-table">
               <tr>
                  <th>         
                     <label>
                        Hashtag
                     </label>
                  </th>
                  <td>
                     <span class="hashtag-symbol">#</span><input type="text" name="nybinstagram_hashtag" class="regular-text code hashtag" value="<?php echo (get_option( 'nybinstagram_hashtag')=='null') ? '' : get_option( 'nybinstagram_hashtag'); ?>">
                  </td>
               </tr>
            </table>
         </div>
         <div class="nybSetting">       
            <table class="form-table">
               <tr>
                  <th>     
                     <p>
                        Follow hashtag?
                     </p>
                  </th>

                  <th>     
                     <p>
                        Follow account?
                     </p>
                  </th>
               </tr>               
               <tr>
                  <td>
                     <label>
                        Yes
                        <input type="radio" name="nybinstagram_follow_hashtag" value="true" <?php  if(get_option( 'nybinstagram_follow_hashtag')=='true') { echo 'checked'; } ?>>
                     </label>         
                     <label>
                        No
                        <input type="radio" name="nybinstagram_follow_hashtag" value="false" <?php  if(get_option( 'nybinstagram_follow_hashtag')!='true') { echo 'checked'; }?>>
                     </label>
                  </td>
                  <td>
                     <label>
                        Yes
                        <input type="radio" name="nybinstagram_follow_account" value="true" <?php  if(get_option( 'nybinstagram_follow_account')=='true') { echo 'checked'; }?>>
                     </label>         
                     <label>
                        No
                        <input type="radio" name="nybinstagram_follow_account" value="false" <?php  if(get_option( 'nybinstagram_follow_account')!='true') { echo 'checked'; }?>>
                     </label>
                  </td>                  
               </tr>
            </table>

             <div class="nybSetting">
                <input type="submit" value="Update Settings" class="button button-primary inline-block"/>
             </div>

      <div class="nybSpacer"></div>

      <div class="nybSetting">       
				<h2>
				   Re-authenticate with Instagram
				</h2>
				<p>
					If you wish to change the account which authenticates your API requests, use the button below to re-authenticate.
				</p>
        <a href="<?php echo $this->getLoginUrl()?>" class="button button-secondary ">Re-auth with Instagram</a>
		</div> 
      <div class="nybSpacer"></div>

      <div class="nybSetting">       
				<h2>
				   API &amp; Account Settings
				</h2>
			</div>      
            <input type="hidden" name="action" value="essential"/>
             <div class="nybSetting">
                <table class="form-table">
                   <tr>
                      <th>   
                         <label>
                            Instagram API-Key (Client ID)
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
                <input type="submit" value="Update Settings" class="button button-primary inline-block"/>
             </div>
        </form>







</div>
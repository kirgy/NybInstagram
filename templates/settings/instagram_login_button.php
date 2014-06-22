<?php

?>


<div class="nybInstagramSettings-wrap">

      <h1>
         NybInstagram Configuration Stage 2 of 3 (account authentication)
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
                Authenticate with Instagram
            </h2>
            <p>
                Some Instagram requests require user authentication. Please authorise the app by select 'Auth with Instagram' below.
            </p>
                <a href="<?php echo $this->getLoginUrl()?>" class="button button-primary ">Auth with Instagram</a>
        </div>      
        <div class="nybSetting">       
            <h2>
               API &amp; Account Settings
            </h2>
            <p>
                If you need to re-update any API settings before authenticating with Instagram, you can do so below.
            </p>
        </div>      
        <form action="" method="post">
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
                <input type="submit" value="Update Settings" class="button button-secondary inline-block"/>
             </div>
        </form>
</div>
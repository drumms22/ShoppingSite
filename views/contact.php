
<?php

/**
* comment.php
*
* I forgot to push this when I wrote it. Sorry about that.
*
* @category   PHP
* @package    CIS-222
* @author     Nicholas Drummonds <ndrummonds@hawkmail.hfcc.edu>
* @version    2020.09.19
* @link       https://cislinux.hfcc.edu/~ndrummonds/cis222/homework2/
*/

?>

    <div class="col pt-5 pb-5 border-dark">
      <div class="col pt-5 pb-5 border-bottom border-notes text-center">

         <h1>Contact us</h1>

      </div>
      <div class="col pt-5 pb-5 border-bottom border-notes text-center justify-content-center">
         <div id="contactMessage"></div>
         <form id="contactForm" class="col-6 offset-md-3 bg-light p-5 border border-notes rounded">
            <div class="form-group">
               <input type="hidden" class="form-control" id="request" value="user/contact">
            </div>
            <div class="form-group">
               <label for="email">Email</label>
               <input type="text" class="form-control" id="email" placeholder="Email">
            </div>
            <div class="form-group">
               <label for="firstname">Firstname</label>
               <input type="text" class="form-control" id="firstname" placeholder="firstname">
            </div>
            <div class="form-group">
               <label for="lastname">Lastname</label>
               <input type="text" class="form-control" id="lastname" placeholder="lastname">
            </div>
            <div class="form-group">
               <label for="message">Message</label>
               <textarea class="form-control" id="message" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
         </form>


      </div>

    </div>
    
    <script type="text/javascript" src="views/scripts/requests.js" ></script>


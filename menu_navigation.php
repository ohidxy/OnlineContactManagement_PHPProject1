
    <p style="margin-top:5px;"><strong>Welcome, <?php echo $_SESSION["fullname"]; ?></strong></p>
    
  <ul class="nav nav-pills nav-justified">
    <li class="<?php echo $isViewContactActive; ?>"><a href="view_contact.php">CONTACTS</a></li>
    
    <li class="<?php echo $isTasksActive; ?>"><a href="tasks.php">TASKS</a></li>    
    <li class="<?php ?>"><a href="">NOTES</a></li>  
    <li class="<?php echo $isAccountActive; ?>"><a href="account.php">Account</a></li>  
      
    <li><a href="logout.php">Log Out</a></li>
  </ul>
    <!-- Feedback and Bug Report Buttons -->
<feedback style="margin-top:5px; float:right;">
    <a class="btn btn-warning btn-sm" href="updates.html" target="_blank">Updates</a>
    <a class="btn btn-warning btn-sm" href="https://goo.gl/forms/dkvJLzxftGfC1AIG3" target="_blank">Bug Report</a>
    <a class="btn btn-warning btn-sm" href="https://goo.gl/forms/w9zM6ECw5qtLKiXH3" target="_blank">Feedback</a>
</feedback>    
<br>



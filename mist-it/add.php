<form action="submit_query.php" name="submit_query" method="post">
   <input type="text" placeholder="Enter Server Name" class="form-control" name = "servername" id = "servername"/>
   <br>
   <br>
   <input type="text" placeholder="Enter Username" class="form-control"  name = "username" id = "username"/>
   <br>
   <br>
   <input type="text" placeholder="Enter Password" class="form-control"
   name = "password" id = "password"/>
   <br>
   <br>
   <input type="text" placeholder="Enter Database Name" class="form-control" name = "database_name" id = "database_name"/>
   <br>
   <br>
    <textarea id="query" class="form-control" rows ="4" name="query"></textarea>
    <br>
    <br>
  <input type="submit" value="Submit" class="submitButton">
</form>
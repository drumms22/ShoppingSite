

<div class="mt-5 col-md-4 offset-md-4">
    <?php
        if(isset($post_results['register'])){

            if(count($post_results['register']['errors']) > 0){

                foreach($post_results['register']['errors'] as $error){

                    echo "<p class='alert alert-danger'>$error</p>";

                }

            }else if(isset($post_results['register']['message'])){

                echo "<p class='alert alert-success'>".$post_results['register']['message']. "</p>";

            }

        }
    ?>
    <form method="POST" action='register'>
    <div class="form-group">
        <label>Email</label>
        <input type="text"  name="email" class="form-control" placeholder="johndoe@gmail.com">
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
        <label for="inputEmail4">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Email">
        </div>
        <div class="form-group col-md-6">
        <label for="inputPassword4">Password confirm</label>
        <input type="password" name="password_confirm" class="form-control" placeholder="Password">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
        <label for="inputEmail4">First name</label>
        <input type="text" name="firstname" class="form-control" placeholder="John">
        </div>
        <div class="form-group col-md-6">
        <label for="inputPassword4">Last name</label>
        <input type="text" name="lastname" class="form-control" placeholder="Doe">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
        <label for="inputEmail4">Birthday</label>
        <input type="text" name="birthday" class="form-control" placeholder="mm/dd/yyyy">
        </div>
        <div class="form-group col-md-6">
        <label for="inputPassword4">Phone</label>
        <input type="number" name="phone" class="form-control" placeholder="1234567890">
        </div>
    </div>
    <div class="form-group">
        <label for="inputAddress">Street</label>
        <input type="text"  name="street" class="form-control" placeholder="1234 Main St">
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
        <label for="inputCity">City</label>
        <input type="text"  name="city" class="form-control">
        </div>
        <div class="form-group col-md-4">
        <label for="inputState">State</label>
        <select name="state" class="form-control">
        <option value="" selected="selected">Select a State</option>
        <option value="AL">Alabama</option>
        <option value="AK">Alaska</option>
        <option value="AZ">Arizona</option>
        <option value="AR">Arkansas</option>
        <option value="CA">California</option>
        <option value="CO">Colorado</option>
        <option value="CT">Connecticut</option>
        <option value="DE">Delaware</option>
        <option value="DC">District Of Columbia</option>
        <option value="FL">Florida</option>
        <option value="GA">Georgia</option>
        <option value="HI">Hawaii</option>
        <option value="ID">Idaho</option>
        <option value="IL">Illinois</option>
        <option value="IN">Indiana</option>
        <option value="IA">Iowa</option>
        <option value="KS">Kansas</option>
        <option value="KY">Kentucky</option>
        <option value="LA">Louisiana</option>
        <option value="ME">Maine</option>
        <option value="MD">Maryland</option>
        <option value="MA">Massachusetts</option>
        <option value="MI">Michigan</option>
        <option value="MN">Minnesota</option>
        <option value="MS">Mississippi</option>
        <option value="MO">Missouri</option>
        <option value="MT">Montana</option>
        <option value="NE">Nebraska</option>
        <option value="NV">Nevada</option>
        <option value="NH">New Hampshire</option>
        <option value="NJ">New Jersey</option>
        <option value="NM">New Mexico</option>
        <option value="NY">New York</option>
        <option value="NC">North Carolina</option>
        <option value="ND">North Dakota</option>
        <option value="OH">Ohio</option>
        <option value="OK">Oklahoma</option>
        <option value="OR">Oregon</option>
        <option value="PA">Pennsylvania</option>
        <option value="RI">Rhode Island</option>
        <option value="SC">South Carolina</option>
        <option value="SD">South Dakota</option>
        <option value="TN">Tennessee</option>
        <option value="TX">Texas</option>
        <option value="UT">Utah</option>
        <option value="VT">Vermont</option>
        <option value="VA">Virginia</option>
        <option value="WA">Washington</option>
        <option value="WV">West Virginia</option>
        <option value="WI">Wisconsin</option>
        <option value="WY">Wyoming</option>
        </select>
        </div>
        <div class="form-group col-md-2">
        <label for="inputZip">Zip</label>
        <input type="text" class="form-control" name="zipcode">
        </div>
    </div>
    <input type="hidden" name="request" value="user/register">
    <div class="col text-center">
    <button type="submit" class="btn btn-primary">Sign up!</button>
    </div>
    </form>
</div>
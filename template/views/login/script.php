<script>

    /**
     * Validate Pass
     * @param string
     * @return string
     */
    function validatePass(pass, r_pass)
    {
        if (pass == "") return "Password Field is empty!"
        else if (r_pass == "") return "Repeat Password Field is empty!"
        else if (pass != r_pass) return "Password and Repeated Password do not match, Try Again!"
        else return ""
    }


    /**
     * Validate full_name
     * @param string
     * @return string
     */
    function validateName(field)
    {
        return (field == "") ? "Name field is empty.\n" : "";
    }// end

    /**
     * Validates email field. The function calls the JavaScript indexOf
     * function two times. First time a check is made to ensure there is
     * a period (.) somewhere from at least the second character of the
     * field, and the second checks that an @ symbol appears somewhere
     * at or after the second character.
     * @param string
     * @return string
     */
    function validateEmail(email)
    {
        re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        if (email == "") return "No Email was entered.\n"
        else if (!( (email.indexOf(".") > 0) && (email.indexOf("@") > 0)) || !re.test(email))
        {
            return "The Email address is invalid.\n"
        }

        return ""
    }// end

    /**
     * Wrapper to : validateEmail,
     * validatePhone
     * @param DOM object from
     */
    function validateInfo(form)
    {
        //fail is a string
        fail = validateEmail(form.register_email.value);
        fail += validateName(form.register_name.value);
        fail += validatePass(form.register_password.value, form.register_password_repeat.value)

        if (fail == "")
        {
            return true;
        }
        else
        {
            alert(fail);
            return false;
        }
    }// end


</script>
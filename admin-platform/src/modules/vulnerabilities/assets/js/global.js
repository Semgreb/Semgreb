
     function confirm_executions() {
          var message = "Are you sure you want to perform this action?";

          // Clients area
          if (typeof app != "undefined") {
               message = app.lang.confirm_action_prompt;
          }

          var r = confirm(message);
          if (r == false) {
               return false;
          }
          return true;
     }

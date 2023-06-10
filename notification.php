<!-- Notification Popup -->
<div id="notification-popup">
  <div class="notification-content">
    <span class="fre-btn primary-bg-color" id="notification-close" style="float:left;"><</span>
      <h3>Notifications</h3>
      <div class="notification-list"></hr></br>
        <?php
        // connect to database
        $servername = "localhost";
        $dbusername = "ewriters_anon";
        $dbpassword = "sfFKcjvsL9RcghJ";
        $dbname = "ewriters_ewriters";

        // create connection
        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

        // check connection
        if ($conn->connect_error) {
          echo "Error connecting to database";
          exit;
        }
        $sql = "SELECT * FROM notifications WHERE recipient={$_SESSION['e_id']}";
        $result = mysqli_query($conn, $sql);
        if ($result) {
          $rows = mysqli_num_rows($result);
          if ($rows > 0) {
            while ($row = $result->fetch_assoc()) {
          $sql1 = "SELECT * FROM users WHERE id={$row['sender']}";
          $result1 = mysqli_query($conn, $sql1);
          $row1 = mysqli_fetch_assoc($result1);
              ?>
              <div style="float:left;width :100%; border-radius:5px; background:cream;">
                <b style="float:left;"><?php echo $row1['f_name']." ".$row1['l_name']; ?></b></br>
                <p style="float:left; text-align:left;">
                  <?php echo $row['message']; ?>
                </p>
              </div>
              <?php
            }
          } else {
            echo "<p>No notification</p>";
          }
        } else {
          echo "Error executing query: " . mysqli_error($conn);
        }
        $conn->close();
        ?>
      </div>
    </div>
  </div>


  <style>
    /* Style for Notification Popup */
    #notification-popup {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.8);
      z-index: 9999;
      display: block;
      display: none;

    }

    .notification-content {
      position: absolute;
      top: 50%;
      left: 50%;
      width: 80%;
      height: 85%;
      transform: translate(-50%, -50%);
      background-color: white;
      border-radius: 10px;
      padding: 10px;
      text-align: center;
      overflow-y: scroll;
    }

    .notification-list {
      max-height: 70%;
      overflow-y: scroll;
    }

    #close-notification-popup {
      margin-top: 10px;
      padding: 5px 20px;
      background-color: #006161;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

  </style>

  <script>
    function openNotif() {
      document.getElementById("notification-button").click();
    }

    var notificationButton = document.getElementById("notification-button");
    var notificationClose = document.getElementById("notification-close");
    var notificationPopup = document.getElementById("notification-popup");

    notificationButton.addEventListener("click", function() {
      notificationPopup.style.display = "block";
    });
    notificationClose.addEventListener("click", function() {
      notificationPopup.style.display = "none";
    });

    notificationPopup.addEventListener("click", function(event) {
      if (event.target == notificationPopup) {
        notificationPopup.style.display = "none";
      }
    });
  </script>
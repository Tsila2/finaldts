<?php
$mysession = $_SESSION['uniqueid'];
$count = count($data);
for ($i = 0; $i < $count; $i++) {
    if ($data[$i]['sender_message_id'] == $mysession) {
        ?>
        <div id="receiver_msg_container">
            <div id="receiver_msg">
                <p class="m-0" id="receiver_ptag">
                    <?php
                    if ($data[$i]['image_path']) {
                        echo '<img src="' . site_url('upload/messages/images/' . $data[$i]['image_path']) . '" alt="" style="width: 100%">';
                    } else if ($data[$i]['audio_path']) {
                        echo '<audio controls src="' . site_url('upload/messages/audios/' . $data[$i]['audio_path']) . '" style="width: 100%"></audio>';
                    } else {
                        echo $data[$i]['message'];
                    }
                    ?>
                </p>
            </div>

            <div id="receiver_image"
                style="background-size: 100% 100%; background-image:url('<?php echo site_url('upload/') . $_SESSION['image']; ?>')">
            </div>
        </div>
        <?php
    } else {
        ?>
        <div id="sender_msg_container">
            <div id="sender_image" style="background-size: 100% 100%; background-image:url('<?php echo $image; ?>')"></div>
            <div id="sender_msg">
                <p class="m-0" id="receiver_ptag">
                    <?php
                    if ($data[$i]['image_path']) {
                        echo '<img src="' . site_url('upload/messages/images/' . $data[$i]['image_path']) . '" alt="" style="width: 100%">';
                    } else if ($data[$i]['audio_path']) {
                        echo '<audio controls src="' . site_url('upload/messages/audios/' . $data[$i]['audio_path']) . '" style="width: 100%"></audio>';
                    } else {
                        echo $data[$i]['message'];
                    }
                    ?>
                </p>
            </div>
        </div>
        <?php
    }
}
?>
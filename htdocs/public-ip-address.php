<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>Your Public IP Address</title>
        <meta charset="utf-8" />
        <style>
            header{
                background-color:steelblue;
                clear:both;
                text-align:center;
            }
            nav{
                background-color:orange;
                clear:both;
                text-align:center;
            }
            footer{
                background-color:steelblue;
                color:white;
                clear:both;
                text-align:center;
            }
            section{
                background-color:silver;
                clear:right;
            }
            article{
                background-color:steelblue;
                clear:right;
            }
            aside{
                background-color:silver;
                float:left;
            }
        </style>
    </head>
    <body>
        <header>
            Header, acknowledgements, menue items<br />
        </header>
        <nav>
            <?php
                $client_ip_address = $_SERVER['REMOTE_ADDR'];
                echo "<h1>Your public IP address is: " . $client_ip_address . "</h1>";
            ?>
        </nav>
        <aside>
            <ul>
                <li>Info</li>
                <li>Geolocation</li>
                <li>Server and Network</li>
                <li>Browser and computer</li>
            </ul>
        </aside>
        <section>
            Section is here.<br />
        </section>
        <article>
            Article is here.<br />
        </article>
        <footer>
            This is the footer.
        </footer>
    </body>
</html>

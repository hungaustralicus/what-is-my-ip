<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>Your Public IP Address</title>
        <!-- Backward compatibility added -->
        <!--[if lt IE 9]>
            <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <style>
            header, section, footer, aside, nav, main, article, figure {
                display: block;
            }
        </style>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="ipstyler.css">
        
    </head>
    <body>
        <nav>
            <ul class="header-ul">
                <li>Header</li>
                <li>Acknowledgements</li>
                <li>Menu items</li>
            </ul>
        </nav>
        <header>
            <?php
                $client_ip_address = $_SERVER['REMOTE_ADDR'];
                echo "<h1>Your public IP address is: " . $client_ip_address . 
                    "</h1>";
            ?>
        </header>
        <aside>
            <ul>
                <li>Info</li>
                <li>Geolocation</li>
                <li>Server and Network</li>
                <li>Browser and computer</li>
            </ul>
        </div>
        <section>
            Section is here.<br />
            <p title="What is IP address?">An <abbr title="Internet Protocol">
                IP</abbr> address is an identifier for devices on a 
                <abbr title="Transmittion Control Protocol">TCP</abbr>/IP 
                network. Typical examples for a TCP/IP network are the Internet
                or a <abbr title="Local Area Network">LAN</abbr>. A 
                <abbr title="Wireless Fidelity">WiFi</abbr> router hosted WLAN
                is also a LAN network.
            </p>
            <p title="Local IP and Public IP">A computer on a local network must
                have a local IP address. If such a computer connects to the 
                Internet its public IP addrress is probably different from its
                local IP address.
            </p>
        
            <article">
                Article is here.<br />
            </article>

            <article>
                Article is here.<br />
            </article>
        </section>
        <article>
           <iframe id="location_wiki" src="https://en.m.wikipedia.org/wiki/Sydney" width=300px height =400% ></iframe>
        </article>
        <footer>        
            This is the footer.
        </footer>
    </body>
</html>
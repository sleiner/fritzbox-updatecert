# 🔒 fritzbox-updatecert
Upload a letsencrypt certificate automatically to an AVM FRITZ!Box

**The original code can be found at: https://community.letsencrypt.org/t/how-to-automatic-certificate-install-on-an-avm-fritzbox/31034 I just added some small modifications.**   

## Usage
1. Enter your FRITZ!Box credentials and the box's URL in the ```fbox-credentials.xml``` file.   
   *Tip: Ideally, create a new user just for this script*
2. Get your certificate from letsencrypt (e.g. using ```certbot```)
3. On a machine with a php interpreter, execute ```./fbox-updatecert.php fbox-credentials.xml /path/to/cert/for/your/doma.in/fullchain.pem /path/to/cert/for/your/doma.in/privkey.pem```

You're Done! 👍
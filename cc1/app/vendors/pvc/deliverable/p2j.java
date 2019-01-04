import java.io.*;
import java.security.KeyStore;
import java.security.cert.Certificate;
import java.util.Enumeration;

public class p2j
{
    public p2j()
    {
    }

    public static void main(String args[])
        throws Exception
    {
        if(args.length < 1)
        {
            System.out.println("usage: java p2j certi.pfx pfxpswd oupt.jks");
            System.exit(1);
        }
        
        File fileIn = new File(args[0]);
        File fileOut=null;
        if(args.length == 3)
        {    fileOut = new File(args[2]);

        }else{
        	System.out.println("usage: java p2j certi.pfx pfxpswd oupt.jks");
        System.exit(1);}
        if(!fileIn.canRead())
        {
            System.out.println("Unable to access input keystore: " + fileIn.getPath());
            System.exit(2);
        }
        if(fileOut.exists() && !fileOut.canWrite())
        {
            System.out.println("Output file is not writable: " + fileOut.getPath());
            System.exit(2);
        }
        KeyStore kspkcs12 = KeyStore.getInstance("pkcs12");
        KeyStore ksjks = KeyStore.getInstance("jks");

        char inphrase[] = args[1].toCharArray();
        char outphrase[] = args[1].toCharArray();
        
        
        kspkcs12.load(new FileInputStream(fileIn), inphrase);
        ksjks.load(fileOut.exists() ? ((java.io.InputStream) (new FileInputStream(fileOut))) : null, outphrase);
        Enumeration eAliases = kspkcs12.aliases();
        int n = 0;
        do
        {
            if(!eAliases.hasMoreElements())
                break;
            String strAlias = (String)eAliases.nextElement();
            if(kspkcs12.isKeyEntry(strAlias))
            {
                java.security.Key key = kspkcs12.getKey(strAlias, inphrase);
                Certificate chain[] = kspkcs12.getCertificateChain(strAlias);
                ksjks.setKeyEntry(strAlias, key, outphrase, chain);
            }
        } while(true);
        OutputStream out = new FileOutputStream(fileOut);
        ksjks.store(out, outphrase);
        out.close();
        System.out.println("Java Key Store created successfully");
    }
}


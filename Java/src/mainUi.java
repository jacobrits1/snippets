import javax.swing.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.*;
import org.sikuli.script.*;
import org.sikuli.script.Screen;
import java.io.ByteArrayInputStream;
import java.io.File;
import java.util.List;
import com.amazonaws.auth.AWSCredentials;
import com.amazonaws.auth.BasicAWSCredentials;
import com.amazonaws.util.StringUtils;
import com.amazonaws.services.s3.AmazonS3;
import com.amazonaws.services.s3.AmazonS3Client;
import com.amazonaws.services.s3.model.Bucket;
import com.amazonaws.services.s3.model.CannedAccessControlList;
import com.amazonaws.services.s3.model.GeneratePresignedUrlRequest;
import com.amazonaws.services.s3.model.GetObjectRequest;
import com.amazonaws.services.s3.model.ObjectListing;
import com.amazonaws.services.s3.model.ObjectMetadata;
import com.amazonaws.services.s3.model.S3ObjectSummary;


/**
 * Created by jacobrits on 2017/02/27.
 */
public class mainUi {
    private JPanel panelMain;
    private JButton buttonStartShoot;
    private JButton buttonShootSetup;
    private JButton buttonAWSsync;
    private JPanel panelShootSetup;
    private JTextPane textShootSetup;
    private JButton buttonAutomation;
    private JButton buttonCyclePower;
    private JTextField textField1;

    public mainUi() {
        buttonStartShoot.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent e) {
                JOptionPane.showMessageDialog(null,"Start Shoot");
                commandLine("cmd.exe");

            }
        });
    }

    private void createUIComponents() {
        // TODO: place custom component creation code here
    }

    public static void commandLine (String command) {

        String s = null;

        try {

            Process p = Runtime.getRuntime().exec(command);

            BufferedReader stdInput = new BufferedReader(new
                    InputStreamReader(p.getInputStream()));

            BufferedReader stdError = new BufferedReader(new
                    InputStreamReader(p.getErrorStream()));


            System.out.println("Here is the standard output of the command:\n");
            while ((s = stdInput.readLine()) != null) {
                System.out.println(s);
            }

            System.out.println("Here is the standard error of the command (if any):\n");
            while ((s = stdError.readLine()) != null) {
                System.out.println(s);
            }

            System.exit(0);
        }
        catch (IOException e) {
            System.out.println("exception happened - here's what I know: ");
            e.printStackTrace();
            System.exit(-1);
        }
    }

    public static void main(String[] args) {
        JFrame panelMain = new JFrame("Fancam Rig Manager");
        panelMain.setContentPane(new mainUi().panelMain);
        panelMain.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        panelMain.pack();
        panelMain.setVisible(true);

    }
}

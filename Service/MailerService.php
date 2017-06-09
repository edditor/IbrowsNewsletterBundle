<?php

namespace Ibrows\Bundle\NewsletterBundle\Service;

use Ibrows\Bundle\NewsletterBundle\Encryption\EncryptionInterface;
use Ibrows\Bundle\NewsletterBundle\Model\Job\MailJob;

class MailerService
{
    /**
     * @var EncryptionInterface
     */
    protected $encryption;

    protected $attachmentsDir;

    /**
     * @param EncryptionInterface $encryption
     */
    public function __construct(EncryptionInterface $encryption, $attachmentsDir)
    {
        $this->encryption = $encryption;
        $this->attachmentsDir = $attachmentsDir;
    }

    protected function getPunycodeMail($email)
    {
        list($localPart, $domainPart) = explode('@', $email);
        return $localPart.'@'.idn_to_ascii($domainPart);
    }

    /**
     * @param  MailJob $job
     * @return array   $failedRecipients
     */
    public function send(MailJob $job, array $attachments = null)
    {
        $toMail = $this->getPunycodeMail($job->getToMail());
        $to = $job->getToName() ? array($toMail => $job->getToName()) : $toMail;

        $message = \Swift_Message::newInstance($job->getSubject(), $job->getBody(), 'text/html', 'utf8')
            ->setFrom(array($job->getSenderMail() => $job->getSenderName()))
            ->setReturnPath($job->getReturnMail())
            ->setTo($to)
        ;
        //TODO get additional headers from database or config
        $headers = $message->getHeaders();
        $headers->addTextHeader('X-Mailer', 'DPX');

        foreach ($job->getAttachments() as $a) {
            $aPath = $this->attachmentsDir.'/'.$a;
            if (is_file($aPath) && is_readable($aPath)) {
                $message->attach(\Swift_Attachment::fromPath($aPath)->setDisposition('inline'));
            }
        }

        $transport = \Swift_SmtpTransport::newInstance($job->getSendSettings()->getHost(), $job->getSendSettings()->getPort())
            ->setUsername($job->getSendSettings()->getUsername())
            ->setPassword($this->encryption->decrypt($job->getSendSettings()->getPassword(), $job->getSalt()))
            ->setEncryption($job->getSendSettings()->getEncryption())
        ;

        //$transport->setAuthMode($job->getAuthMode());

        $mailer = \Swift_Mailer::newInstance($transport);

        $failedRecipients = array();
        $mailer->send($message, $failedRecipients);

        return $failedRecipients;
    }

}

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

    /**
     * @param  MailJob $job
     * @return array   $failedRecipients
     */
    public function send(MailJob $job, array $attachments = null)
    {
        $to = $job->getToName() ? array($job->getToMail() => $job->getToName()) : $job->getToMail();

        $message = \Swift_Message::newInstance($job->getSubject(), $job->getBody(), 'text/html', 'utf8')
            ->setFrom(array($job->getSenderMail() => $job->getSenderName()))
            ->setReturnPath($job->getReturnMail())
            ->setTo($to)
        ;
        foreach ($job->getAttachments() as $a) {
            $message->attach(\Swift_Attachment::fromPath($this->attachmentsDir.'/'.$a)->setDisposition('inline'));
        }

        $transport = \Swift_SmtpTransport::newInstance($job->getHost(), $job->getPort())
            ->setUsername($job->getUsername())
            ->setPassword($this->encryption->decrypt($job->getPassword(), $job->getSalt()))
            ->setEncryption($job->getEncryption())
        ;

        //$transport->setAuthMode($job->getAuthMode());

        $mailer = \Swift_Mailer::newInstance($transport);

        $failedRecipients = array();
        $mailer->send($message, $failedRecipients);

        return $failedRecipients;
    }

}

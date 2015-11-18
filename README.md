IbrowsNewsletterBundle
======================

A nice Symfony2 Newsletter Bundle

Features:

- Different Templates
- Handling different chanels and groups
- E-Mail Body richt text editor
- Wizard for creating Newsletters
- Body-Block handling
- E-Mail messages queuing
- Statistics
- Different Users for each Newsletter

The sandbox
===========

Have a look on our sandbox: https://github.com/ibrows/IbrowsNewsletterBundleSandbox
Demo installation: http://newsletterbundle.ibrhost.com (User: demo / Pass: demo)

How to install
==============

### Add Bundle to your composer.json

```js
// composer.json

{
    "require": {
        "ibrows/newsletter-bundle": "*"
    }
}
```

### Install the bundle from console with composer.phar

``` bash
$ php composer.phar update ibrows/newsletter-bundle
```

### Enable the bundles in AppKernel.php

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),
        new Ibrows\Bundle\NewsletterBundle\IbrowsNewsletterBundle(),
    );
}
```

### Configuration

``` yaml
# app/config/ibrows_newsletter.yml

ibrows_newsletter:
  mandants:
    # generate a secure token for each mandant!
    default:    ThisTokenIsNotSoSecretChangeItdefault
    mandantA:   ThisTokenIsNotSoSecretChangeItMandantA
    mandantB:   ThisTokenIsNotSoSecretChangeItMandantB
  classes:
    # needed entities - see next step for creating them
    model:
      # most likely fos user
      user:         Ibrows\YourBundle\Entity\User
      # depends on the namespace you set at
      mandant:      Ibrows\YourBundle\Entity\Newsletter\Mandant
      newsletter:   Ibrows\YourBundle\Entity\Newsletter\Newsletter
      subscriber:   Ibrows\YourBundle\Entity\Newsletter\Subscriber
      design:       Ibrows\YourBundle\Entity\Newsletter\Design
      block:        Ibrows\YourBundle\Entity\Newsletter\Block
      group:        Ibrows\YourBundle\Entity\Newsletter\Group
      readlog:      Ibrows\YourBundle\Entity\Newsletter\Log\ReadLog
      sendlog:      Ibrows\YourBundle\Entity\Newsletter\Log\SendLog
      sendsettings: Ibrows\YourBundle\Entity\Newsletter\SendSettings
      mailjob:      Ibrows\YourBundle\Entity\Newsletter\MailJob

  filesystem:
      block:
        # where to store uploaded files (e.g. image uploads)
        directory:  %kernel.root_dir%/../web/uploads/block
        # absolute path to the uploaded files
        public:     /uploads/block
```

``` yaml
# app/config/routing.yml

# IbrowsNewsletter
ibrows_newsletter:
    resource: "@IbrowsNewsletterBundle/Controller/"
    type:     annotation
    prefix:   /newsletter
```

```yaml
# app/config/stfalcon_tinymce.yml

stfalcon_tinymce:
  include_jquery: false
  tinymce_jquery: true
  selector: ".tinymce"
  # create own methods in your own RendererBridge and set here the icons and description for them
  tinymce_buttons:
    unsubscribelink:
      title: "Unsubscribe link"
      image: "http://placehold.it/30x30"
    now:
      title: "Current date"
      image: "http://placehold.it/30x30"
    gendertitle:
      title: "Gender title"
      image: "http://placehold.it/30x30"
    statisticlogreadimage:
      title: "Statistics image"
      image: "http://placehold.it/30x30"
    readonlinelink:
      title: "Read online link"
      image: "http://placehold.it/30x30"
  theme:
    simple:
      mode: "textareas"
      theme: "advanced"
      plugins: "fullscreen,table"
      theme_advanced_buttons2: "unsubscribelink,now,gendertitle,statisticlogreadimage,readonlinelink"
      #theme_advanced_buttons2: "tablecontrols"
```

``` yaml
# app/config/config.yml

imports:
    # ...
    - { resource: stfalcon_tinymce.yml }
    - { resource: ibrows_newsletter.yml }
```

### Add NoStreamBlob DBAL Type

```yaml
# app/config/config.yml

# Doctrine Configuration
    doctrine:
        dbal:
            types:
                nostreamblob: Ibrows\Bundle\NewsletterBundle\DBAL\Types\NoStreamBlobType
```

### Configure mandants

The allowed mandants are already defined under ibrows_newsletter.mandants in ibrows_newsletter.yml.
It is possible to run each mandant on an own database. For that we have to inform doctrine about the different connections:

``` yaml
# app/config/config.yml

# Doctrine Configuration
doctrine:
    dbal:
        # ...
        connections:
            default:
                driver:   %database_driver%
                host:     %database_host%
                port:     %database_port%
                dbname:   %database_name%
                user:     %database_user%
                password: %database_password%
                charset:  UTF8
            mandantA:
                driver:   %database_driver_mandanta%
                host:     %database_host_mandanta%
                port:     %database_port_mandanta%
                dbname:   %database_name_mandanta%
                user:     %database_user_mandanta%
                password: %database_password_mandanta%
                charset:  UTF8
            mandantB:
                driver:   %database_driver_mandantb%
                host:     %database_host_mandantb%
                port:     %database_port_mandantb%
                dbname:   %database_name_mandantb%
                user:     %database_user_mandantb%
                password: %database_password_mandantb%

    orm:
        # ...
        entity_managers:
            default:
                connection: default
                mappings:
                    YourBundle: ~
                    # FOSUserBundle: ~
                    # ...
            mandantA:
                connection: mandantA
                mappings:
                    ibrows_newsletter:
                        type: annotation
                        prefix: YourBundle\Entity\Newsletter
                        dir: "%kernel.root_dir%/../src/Ibrows/YourBundle/Entity/Newsletter"
                        is_bundle: false
            mandantB:
                connection: mandantB
                mappings:
                    ibrows_newsletter:
                        type: annotation
                        prefix: YourBundle\Entity\Newsletter
                        dir: "%kernel.root_dir%/../src/Ibrows/YourBundle/Entity/Newsletter"
                        is_bundle: false
```

### Connection settings

```yaml
# app/config/parameters.yml

parameters:
    database_driver:   pdo_mysql
    database_host:     127.0.0.1
    database_port:     ~
    database_name:     newslettersandbox
    database_user:     user
    database_password: pass

    database_driver_mandanta:   pdo_mysql
    database_host_mandanta:     127.0.0.1
    database_port_mandanta:     ~
    database_name_mandanta:     newslettersandbox_mandanta
    database_user_mandanta:     user
    database_password_mandanta: pass

    database_driver_mandantb:   pdo_mysql
    database_host_mandantb:     127.0.0.1
    database_port_mandantb:     ~
    database_name_mandantb:     newslettersandbox_mandantb
    database_user_mandantb:     user
    database_password_mandantb: pass
```

### Enable configured mandants

``` bash
# create the databases
$ php app/console doctrine:schema:create --em default
$ php app/console doctrine:schema:create --em mandantA
$ php app/console doctrine:schema:create --em mandantB

# enable the mandants (insert them in the defined database - already existings will be ignored)
$ php app/console ibrows:newsletter:mandants:enable
```

### Generate needed entities

``` bash
$ php app/console ibrows:newsletter:entities:generate Ibrows\\YourBundle\\Entity
```

### Pimp up your user class - implementing the MandantUserInterface

> The idea behind this concept is a user entity which is authenticated over symfony security component (most likely always stored in the default connection - database).
> This entity now need to implement the MandantUserInterface to let the IbrowsNewsletterBundle know which mandant should be used.

```php
# YourBundle\Entity\User (e.g. FOSUser implementation)

<?php

namespace YourBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

use Ibrows\Bundle\NewsletterBundle\Model\User\MandantUserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser implements MandantUserInterface
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $mandant;

    /**
     * @return string
     */
    public function getMandant()
	{
		return $this->mandant;
	}
}
```

### Connect a user (e.g. FOSUser) from the default connection with a mandant

``` sql
    UPDATE `fos_user` SET mandant = "default" WHERE username = "YourUsername";
    UPDATE `fos_user` SET mandant = "mandantA" WHERE username = "YourUsernameA";
    UPDATE `fos_user` SET mandant = "mandantB" WHERE username = "YourUsernameB";
```

### Starting

- Open your project in the browser
- Authenticate with a user which has a valid mandant given
- Open /newsletter (app_dev.php/newsletter)
- Create a Design
- Create a Newsletter
- Have fun

### Customization

- Implementing an own GenderTitleStrategy for setting the correct salutation
- Implementing an own RendererBridge for providing more methods

### Roadmap

- Implementing frontend inline editing

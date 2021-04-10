<?php
namespace App\Tests;

use App\Entity\Calendar;
use App\Entity\User;
use App\Entity\Contact;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ContactTest extends KernelTestCase {

    public function testCreateContact () {
        $code = (new Contact())
            ->setFirstName('Jean')
            ->setLastName('Locos')
            ->setEmail('email@email.fr');

    self::bootKernel();
    $error = self::$container->get('validator')->validate($code);
    $this->assertCount(0, $error);
    }

}

class CalendarTest extends KernelTestCase {

    public function testCreateEvent () {
        $code = (new Calendar())
            ->setTitle("Titre de l'évènement")
            ->setDescription('description');

        self::bootKernel();
        $error = self::$container->get('validator')->validate($code);
        $this->assertCount(0, $error);
    }

}



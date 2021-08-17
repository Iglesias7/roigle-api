<?php

namespace App\DataFixtures;

use App\Entity\Car;
use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Compte;
use App\Entity\Immobilier;
use App\Entity\Post;
use App\Entity\Pret;
use App\Entity\Transaction;
use App\Entity\Vote;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        $car1 = new Car();
        $car1->setMark("Toyota")
            ->setName("Yaris")
            ->setPrice(25000);
        $manager->persist($car1);

        $car2 = new Car();
        $car2->setMark("Mazda")
            ->setName("df")
            ->setPrice(25000);
        $manager->persist($car2);

        $car3 = new Car();
        $car3->setMark("Toyota")
            ->setName("Rav 4")
            ->setPrice(4000);
        $manager->persist($car3);

        $car4 = new Car();
        $car4->setMark("Toyota")
            ->setName("Corolla")
            ->setPrice(75000);
        $manager->persist($car4);

        $car5 = new Car();
        $car5->setMark("Mercedes")
            ->setName("Benz")
            ->setPrice(250000);
        $manager->persist($car5);

        $immobilier = new Immobilier();
        $immobilier->setName("Appartement")->setType(["Appartement"])
            ->setPrice(2000);

        $compte1 = new Compte();
        $compte2 = new Compte();
        $compte3 = new Compte();
        $compte4 = new Compte();
        $compte5 = new Compte();
        $compte6 = new Compte();

        $user1 = new User();
        $user1->setLastName("Daniel")
            ->setFirstName("Blonde")
            ->setEmail("blonde@gmail.com")
            ->setPassword($this->passwordEncoder->encodePassword($user1, "chendjou"))
            ->addCar($car1)->setCompte($compte1);

        $manager->persist($user1);
        $compte1->setUser($user1);
        $manager->persist($compte1);

        $user2 = new User();
        $user2->setLastName("Lacroix")
            ->setFirstName("Bruno")
            ->setEmail("bruno@gmail.com")
            ->setPassword($this->passwordEncoder->encodePassword($user2, "bruno.l"))
            ->addCar($car2)
            ->addFollower($user1)->setCompte($compte2);

        $manager->persist($user2);
        $compte2->setUser($user2);
        $manager->persist($compte2);

        $user3 = new User();
        $user3->setLastName("Boris")
            ->setFirstName("Verhaegen")
            ->setEmail("boris@gmail.com")
            ->setPassword($this->passwordEncoder->encodePassword($user3, "boris.v"))
            ->addCar($car1)->setCompte($compte3);

        $manager->persist($user3);
        $compte3->setUser($user3);
        $manager->persist($compte3);

        $user4 = new User();
        $user4->setLastName("Benoit")
            ->setFirstName("Penelle")
            ->setEmail("ben@gmail.com")
            ->setPassword($this->passwordEncoder->encodePassword($user4, "benoit"))
            ->addCar($car3)->setCompte($compte4);
            
        $manager->persist($user4);
        $compte4->setUser($user4);
        $manager->persist($compte4);

        $user5 = new User();
        $user5->setLastName("Alain")
            ->setFirstName("Silovy")
            ->setEmail("alain@gmail.com")
            ->setPassword($this->passwordEncoder->encodePassword($user5, "alain"))
            ->addCar($car4)
        ->addImmobilier($immobilier)->setCompte($compte5);
       
        $manager->persist($user5);
        $compte5->setUser($user5);
        $manager->persist($compte5);

        $user6 = new User();
        $user6->setLastName("Iglesias")
            ->setFirstName("Chendjou")
            ->setEmail("iglesias@gmail.com")
            ->setPassword($this->passwordEncoder->encodePassword($user5, "iglesias"))
            ->addCar($car5)->setCompte($compte6);

        $manager->persist($user6);
        $compte6->setUser($user6);
        $manager->persist($compte6);




        $post1 = new Post();

        $post1->setTitle("What does 'initialization' exactly mean?")
            ->setBody("My csapp book says that if global and static variables are initialized, than they are contained in .data section in ELF relocatable object file.
            So my question is that if some `foo.c` code contains 
            ```
            int a;
            int main()
            {
                a = 3;
            }`
            ```
            and `example.c` contains,
            ```
            int b = 3;
            int main()
            {
            ...
            }
            ```
            is it only `b` that considered to be initialized? In other words, does initialization mean declaration and definition in same line?")
            ->setTimestamp(new \DateTime("2019-11-15"))
            ->setUser($user1);
                        
        $manager->persist($post1);

        $post2 = new Post();

        $post2->setBody("It means exactly what it says. Initialized static storage duration objects will have their init values set before the main function is called. Not initialized will be zeroed. The second part of the statement is actually implementation dependant,  and implementation has the full freedom of the way it will be archived. 
            When you declare the variable without the keyword `extern`  you always define it as well")
            ->setTimestamp(new \DateTime("2019-11-15"))
            ->setUser($user2)
            ->setParent($post1);
            $post1->addResponse($post2);
                        
        $manager->persist($post2);
        $manager->persist($post1);

        $post3 = new Post();

        $post3->setBody("Both are considered initialized
        ------------------------------------
        They get [zero initialized][1] or constant initalized (in short: if the right hand side is a compile time constant expression).
        > If permitted, Constant initialization takes place first (see Constant
        > initialization for the list of those situations). In practice,
        > constant initialization is usually performed at compile time, and
        > pre-calculated object representations are stored as part of the
        > program image. If the compiler doesn't do that, it still has to
        > guarantee that this initialization happens before any dynamic
        > initialization.
        > 
        > For all other non-local static and thread-local variables, Zero
        > initialization takes place. In practice, variables that are going to
        > be zero-initialized are placed in the .bss segment of the program
        > image, which occupies no space on disk, and is zeroed out by the OS
        > when loading the program.
        To sum up, if the implementation cannot constant initialize it, then it must first zero initialize and then initialize it before any dynamic initialization happends.
          [1]: https://en.cppreference.com/w/cpp/language/zero_initialization
        ")
            ->setTimestamp(new \DateTime("2019-11-15"))
            ->setUser($user3)
            ->setParent($post1);
            $post1->addResponse($post3);
                        
        $manager->persist($post3);
        $manager->persist($post1);

        $post4 = new Post();

        $post4->setTitle("How do I escape characters in an Angular date pipe?")
            ->setBody("I have an Angular date variable `today` that I'm using the [date pipe][1] on, like so:
            {{today | date:'LLLL d'}}
        > February 13
        However, I would like to make it appear like this:
        > 13 days so far in February
        When I try a naive approach to this, I get this result:
            {{today | date:'d days so far in LLLL'}}
        > 13 13PM201818 18o fPMr in February
        This is because, for instance `d` refers to the day.
        How can I escape these characters in an Angular date pipe? I tried `\d` and such, but the result did not change with the added backslashes.
          [1]: https://angular.io/api/common/DatePipe")
            ->setTimestamp( new \DateTime("2019-11-15"))
            ->setUser($user1);
                        
        $manager->persist($post4);

        $post5 = new Post();

        $post5->setBody("How about this:
            {{today | date:'d \'days so far in\' LLLL'}}
        Anything inside single quotes is ignored. Just don't forget to escape them.")
            ->setTimestamp(new \DateTime("2019-11-15"))
            ->setUser($user1)
            ->setParent($post4);

            $post4->addResponse($post5);
                        
        $manager->persist($post5);
        $manager->persist($post4);

        $post6 = new Post();

        $post6->setBody("Then only other alternative to stringing multiple pipes together as suggested by RichMcCluskey would be to create a custom pipe that calls through to momentjs format with the passed in date. Then you could use the same syntax including escape sequence that momentjs supports.
        Something like this could work, it is not an exhaustive solution in that it does not deal with localization at all and there is no error handling code or tests.
            import { Inject, Pipe, PipeTransform } from '@angular/core';
            @Pipe({ name: 'momentDate', pure: true })
            export class MomentDatePipe implements PipeTransform {
                transform(value: any, pattern: string): string {
                    if (!value)
                        return '';
                    return moment(value).format(pattern);
                }
            }
        And then the calling code:
            {{today | momentDate:'d [days so far in] LLLL'}}
        For all the format specifiers see the [documentation for format][1]. 
        Keep in mind you do have to import `momentjs` either as an import statement, have it imported in your cli config file, or reference the library from the root HTML page (like index.html).
          [1]: http://momentjs.com/docs/#/displaying/format/")
            ->setTimestamp(new \DateTime("2019-11-15"))
            ->setUser($user3)
            ->setParent($post4);
            $post4->addResponse($post6);
                        
        $manager->persist($post6);
        $manager->persist($post4);

        $post7 = new Post();

        $post7->setBody("As far as I know this is not possible with the Angular date pipe at the time of this answer. One alternative is to use multiple date pipes like so:
        {{today | date:'d'}} days so far in {{today | date:'LLLL'}}
    EDIT:
    After posting this I tried @Gh0sT 's solution and it worked, so I guess there is a way to use one date pipe.")
            ->setTimestamp(new \DateTime("2019-11-15"))
            ->setUser($user2)
            ->setParent($post4);
            $post4->addResponse($post7);
                        
        $manager->persist($post7);
        $manager->persist($post4);

        $post8 = new Post();

        $post8->setTitle("Q1")
            ->setBody("Q1")
            ->setTimestamp(new \DateTime("2019-11-22"))
            ->setUser($user5);
                        
        $manager->persist($post8);

        $post9 = new Post();

        $post9->setBody("R1")
            ->setTimestamp(new \DateTime("2019-11-22"))
            ->setUser($user1)
            ->setParent($post8);
            $post8->addResponse($post9);
                        
        $manager->persist($post9);
        $manager->persist($post8);

        $post10 = new Post();

        $post10->setBody("R2")
            ->setTimestamp(new \DateTime("2019-11-22"))
            ->setUser($user2)
            ->setParent($post8);
            $post8->addResponse($post10);
                        
        $manager->persist($post10);
        $manager->persist($post8);

        $post11 = new Post();

        $post11->setBody("R3")
            ->setTimestamp(new \DateTime("2019-11-22"))
            ->setUser($user3)
            ->setParent($post8);
            $post8->addResponse($post11);
                        
        $manager->persist($post11);
        $manager->persist($post8);

        $post12 = new Post();

        $post12->setTitle("Q2")
            ->setBody("Q2")
            ->setTimestamp(new \DateTime("2019-11-22"))
            ->setUser($user4);
                        
        $manager->persist($post12);

        $post13 = new Post();

        $post13->setBody("R4")
            ->setTimestamp(new \DateTime("2019-11-22"))
            ->setUser($user5)
            ->setParent($post12);
            $post12->addResponse($post13);
                        
        $manager->persist($post13);
        $manager->persist($post12);

        $post14 = new Post();

        $post14->setTitle("Q3")
            ->setBody("Q3")
            ->setTimestamp(new \DateTime("2019-11-22"))
            ->setUser($user1);
                        
        $manager->persist($post14);

        $post15 = new Post();

        $post15->setBody("R5")
            ->setTimestamp(new \DateTime("2019-11-22"))
            ->setUser($user5)
            ->setParent($post14);
            $post14->addResponse($post15);
                        
        $manager->persist($post15);
        $manager->persist($post14);

        $post16 = new Post();

        $post16->setBody("R6")
            ->setTimestamp(new \DateTime("2019-11-22"))
            ->setUser($user3)
            ->setParent($post14);
            $post14->addResponse($post16);
                        
        $manager->persist($post16);
        $manager->persist($post14);

        $post17 = new Post();

        $post17->setTitle("Q4")
            ->setBody("Q4")
            ->setTimestamp(new \DateTime("2019-11-22"))
            ->setUser($user2);
                        
        $manager->persist($post17);

        $post18 = new Post();

        $post18->setBody("R7")
            ->setTimestamp(new \DateTime("2019-11-22"))
            ->setUser($user3)
            ->setParent($post17);
            $post17->addResponse($post18);
                        
        $manager->persist($post18);
        $manager->persist($post17);

        $post19 = new Post();

        $post19->setTitle("Q5")
            ->setBody("Q8")
            ->setTimestamp(new \DateTime("2019-11-22"))
            ->setUser($user4);
                        
        $manager->persist($post19);

        $post20 = new Post();

        $post20->setBody("R8")
            ->setTimestamp(new \DateTime("2019-11-22"))
            ->setUser($user3)
            ->setParent($post19);
            $post19->addResponse($post20);
                        
        $manager->persist($post20);
        $manager->persist($post19);

        $comment1 = new Comment();

        $comment1->setBody('Global ""uninitialized"" variables typically end up in a ""bss"" segment, which will be initialized to zero.')
            ->setTimestamp(new \DateTime("2019-11-15"))
            ->setUser($user1)
            ->setPost($post1);
                        
        $manager->persist($comment1);

        $comment2 = new Comment();

        $comment2->setBody("[stackoverflow.com/questions/1169858/…]() This might help")
            ->setTimestamp(new \DateTime("2019-11-15"))
            ->setUser($user2)
            ->setPost($post1);
                        
        $manager->persist($comment2);

        $comment3 = new Comment();

        $comment3->setBody("Verified that this works! Pretty cool")
            ->setTimestamp(new \DateTime("2019-11-15"))
            ->setUser($user2)
            ->setPost($post6);
                        
        $manager->persist($comment3);

        $comment4 = new Comment();

        $comment4->setBody('For me it works with double quotes. `{{today | date:""d \days so far in\ LLLL""}}`')
            ->setTimestamp(new \DateTime("2019-11-15"))
            ->setUser($user3)
            ->setPost($post7);
                        
        $manager->persist($comment4);

        $comment5 = new Comment();

        $comment5->setBody("This does not provide an answer to the question. Once you have sufficient reputation you will be able to comment on any post; instead, provide answers that don't require clarification from the asker.")
            ->setTimestamp(new \DateTime("2019-11-15"))
            ->setUser($user2)
            ->setPost($post6);
                        
        $manager->persist($comment5);

        $comment6 = new Comment();

        $comment6->setBody("Duplicate of [xxx](yyy). Please stop!")
            ->setTimestamp(new \DateTime("2019-11-15"))
            ->setUser($user1)
            ->setPost($post6);
                        
        $manager->persist($comment6);


        $vote1 = new Vote();

        $vote1->setUpdown(1)
            ->setPost($post1)
            ->setUser($user5);
                        
        $manager->persist($vote1);

        $vote2 = new Vote();

        $vote2->setUpdown(1)
            ->setPost($post2)
            ->setUser($user3);
                        
        $manager->persist($vote2);

        $vote3 = new Vote();

        $vote3->setUpdown(-1)
            ->setPost($post1)
            ->setUser($user2);
                        
        $manager->persist($vote3);

        $vote4 = new Vote();

        $vote4->setUpdown(-1)
            ->setPost($post1)
            ->setUser($user3);
                        
        $manager->persist($vote4);

        $vote5 = new Vote();

        $vote5->setUpdown(1)
            ->setPost($post3)
            ->setUser($user2);
                        
        $manager->persist($vote1);
                        
        $manager->flush();
    }
}

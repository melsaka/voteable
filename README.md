# Voteable

The Laravel Voteable package provides a convenient way to implement a voting system in your Laravel applications. With this package, you can easily allow users (voters) to vote on various models (voteables) in your application, such as posts, comments, or any other voteable content.

## Installation

Add the package to your Laravel app via Composer:

```bash
composer require melsaka/voteable
```

Register the package's service provider in config/app.php.

```php
'providers' => [
    ...
    Melsaka\Voteable\VoteableServiceProvider::class,
    ...
];
```

Run the migrations to add the required table to your database:

```bash
php artisan migrate
```

Add `Voter` trait to the voter model, `User` model for example:

```php
use Melsaka\Voteable\Voter;

class User extends Model
{
    use Voter;
    
    // ...   
}
```

Add `Voteable` trait to your voteable model, `Post` model for example:

```php
use Melsaka\Voteable\Voteable;

class Post extends Model
{
    use Voteable;
    
    // ...   
}
```

## Configuration

To configure the package, publish its configuration file:

```bash
php artisan vendor:publish --tag=voteable
```

You can then modify the configuration file to change the votes table name if you want, default: `votes`.

## Usage

The Laravel Voteable package provides a variety of methods to handle voting. Here are some of the key functionalities:

For the sake of demonstration, We are going to use `User`, and `Post` Models as examples, where `$user` is the voter, and `$post` is voteable.

```php
$user = User::first(); // voter
$post = Post::first(); // voteable
```

### Voting on a Post

You can vote up or down on a post using the following methods:

```php
// Vote up to this post by a voter (user)
Vote::up($post, $user);

// Vote down to this post by a voter (user)
Vote::down($post, $user);

// Remove a voter's (user's) vote on this post
Vote::remove($post, $user);
```

### Voting on a Post

You can check if a voter (user) has voted on a post using the following method:

```php
// Check if a voter (user) has voted on this post
Vote::has($post, $user);
```

### Alternative Voting Methods

You can also use the methods provided by the voteable and voter instances:

```php
// Vote up a post using the post instance
$post->upVote($user);

// Vote down a post using the post instance
$post->downVote($user);

// Remove a vote on a post using the post instance
$post->removeVote($user);

// Check if a user has voted on a post using the post instance
$post->hasVote($user);

// You can perform similar actions using the voter instance
$user->upVote($post);
$user->downVote($post);
$user->removeVote($post);
$user->hasVote($post);
```

### Retrieving Voters and Voted Items

You can retrieve all voters (users) who voted on a specific voteable item and all voted items by a specific voter (user):

```php
// Get all voters (users) who voted on a post
$post->voters(User::class)->get();

// Get all voted posts by a voter (user)
$user->voteables(Post::class)->get();
```

### Eager Loading Vote Data

You can also eager load the number of votes and their sum for voteable models:

```php
// Eager load the total sum of the post votes
Post::withVotesSum()->get();
$post->loadVotesSum();

// Eager load the number of the post votes
Post::withVotesCount()->get();
$post->loadVotesCount();

// Eager load only the number of the post up votes
Post::withUpVotesCount()->get();
$post->loadUpVotesCount();

// Eager load only the number of the post down votes
Post::withDownVotesCount()->get();
$post->loadDownVotesCount();
```

### Ordering by Votes

You can order voteable models (e.g., posts) by their vote counts or vote sums:

```php
// Order posts by the number of votes
Post::orderByVotesCount()->get();

// Order posts by the total sum of votes
Post::orderByVotesSum()->get();
```

### Checking if a Voter Voted on Multiple Items

You can get posts and check if a specific voter (user) voted on them:

```php
Post::withVoted($user);
```

### Accessing the Votes Relation

You can access the votes relation through the votes() method:

```php
// Access the votes relation for a post
$post->votes();

// Access the votes relation for a voter (user)
$user->votes();
```

### Database Schema

To store the voting data, the package uses the following database schema:

```php
Schema::create('votes', function (Blueprint $table) {
    $table->id();
    $table->morphs('voteable');
    $table->morphs('voter');
    $table->tinyInteger('vote');
    $table->timestamps();
});
```

## License

This package is released under the MIT license (MIT).

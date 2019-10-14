<?php

namespace Tests\Feature;

use App\Task;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TasksTest extends TestCase
{

    use DatabaseMigrations;

    public function testUserSeeTasks()
    {
        //Given we have task in the database
        $task = factory(Task::class)->create();

        //When user visit the tasks page
        $response = $this->get('/tasks');

        //He should be able to read the task
        $response->assertSee($task->title);
    }

    public function testUserCanReadSingleTask()
    {
        //Given we have task in the database
        $task = factory(Task::class)->create();

        //When user visit the tasks page
        $response = $this->get('/tasks/' . $task->id);

        //He should be able to read the task
        $response->assertSee($task->title)
            ->assertSee($task->description);
    }

    public function testTitleFieldIsRequiredWhenCreate()
    {
        $this->actingAs(factory(User::class)->create());

        // Make task data without storing
        $task = factory(Task::class)->make([
            'title' => null
        ]);

        $this->post('/tasks', $task->toArray())
            ->assertSessionHasErrors('title');
    }

    public function testDescriptionFieldIsRequiredWhenCreate()
    {
        $this->actingAs(factory(User::class)->create());

        // Make task data without storing
        $task = factory(Task::class)->make([
            'description' => null
        ]);

        $this->post('/tasks', $task->toArray())
            ->assertSessionHasErrors('description');
    }

    public function testAuthenticatedUserCanCreateTask()
    {

        //Auth user
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Make task data without storing
        $task = factory(Task::class)->make();

        // send request to store data
        $this->post('/tasks', $task->toArray());

        // check task in database
        $this->assertDatabaseHas('tasks', [
            'title' => $task->title,
            'description' => $task->description,
            'user_id' => $user->id,
        ]);
    }

    public function testTitleFieldHasValidationWhenUpdate()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Make task data without storing
        $task = factory(Task::class)->create([
            'user_id' => $user->id
        ]);

        $newData = $task->toArray();
        $newData['title'] = '';

        $this->put('/tasks/' . $task->id, $newData)
            ->assertSessionHasErrors('title');
    }

    public function testDescriptionFieldHasValidationWhenUpdate()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Make task data without storing
        $task = factory(Task::class)->create([
            'user_id' => $user->id
        ]);

        $newData = $task->toArray();
        $newData['description'] = '';

        $this->put('/tasks/' . $task->id, $newData)
            ->assertSessionHasErrors('description');
    }

    public function testAuthenticatedUserCanUpdateTask()
    {

        //Auth user
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Make task and new data
        $task = factory(Task::class)->create([
            'user_id' => $user->id
        ]);

        // check task in database
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'user_id' => $user->id,
        ]);

        $dataForUpdate = factory(Task::class)->make();

        // send request to update data
        $this->put('/tasks/' . $task->id, $dataForUpdate->toArray());

        // check task in database
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => $dataForUpdate->title,
            'description' => $dataForUpdate->description,
            'user_id' => $user->id,
        ]);
    }

    public function testAuthenticatedUserCanDeleteOwnTask()
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create(['user_id' => $user->id]);

        $testData = [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'user_id' => $user->id,
        ];

        $this->assertDatabaseHas('tasks', $testData);

        $this->actingAs($user);
        $this->delete('tasks/' . $task->id);
        $this->assertDatabaseMissing('tasks', $testData);
    }

    public function testUnauthorizedUserCanNotUpdateTask()
    {
        $task = factory(Task::class)->create();
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $newData = [
            'title' => 'Updated title',
            'description' => 'Updated description'
        ];

        $this->put(route('tasks.update', ['task'=>$task->id]), $newData)
        ->assertForbidden();
    }

    public function testUnauthorizedUserCanNotDeleteTask()
    {
        $task = factory(Task::class)->create();
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $this->delete(route('tasks.destroy', ['task'=>$task->id]))
            ->assertForbidden();
    }
}

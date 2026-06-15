<?php

namespace Tests\Feature;

use App\Models\Classification;
use App\Models\Subclassification;
use App\Models\Qualification;
use App\Models\WorkPosition;
use App\Models\Lsp;
use App\Models\Association;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterDataTest extends TestCase
{
    use RefreshDatabase;

    // ==========================================
    // Classification API Tests
    // ==========================================
    public function test_can_manage_classifications(): void
    {
        // 1. Get Classifications (Empty)
        $response = $this->getJson(route('api.master.classifications.index'));
        $response->assertStatus(200)->assertJson([]);

        // 2. Create Classification
        $data = ['nama' => 'SIPIL'];
        $response = $this->postJson(route('api.master.classifications.store'), $data);
        $response->assertStatus(201)->assertJsonFragment(['nama' => 'SIPIL']);
        $this->assertDatabaseHas('classifications', ['nama' => 'SIPIL']);

        $classification = Classification::first();

        // 3. Update Classification
        $updateData = ['nama' => 'ARSITEKTUR'];
        $response = $this->putJson(route('api.master.classifications.update', $classification->id), $updateData);
        $response->assertStatus(200)->assertJsonFragment(['nama' => 'ARSITEKTUR']);
        $this->assertDatabaseHas('classifications', ['nama' => 'ARSITEKTUR']);
        $this->assertDatabaseMissing('classifications', ['nama' => 'SIPIL']);

        // 4. Delete Classification
        $response = $this->deleteJson(route('api.master.classifications.destroy', $classification->id));
        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertDatabaseMissing('classifications', ['nama' => 'ARSITEKTUR']);
    }

    // ==========================================
    // Subclassification API Tests
    // ==========================================
    public function test_can_manage_subclassifications(): void
    {
        $classification = Classification::create(['nama' => 'SIPIL']);

        // 1. Get Subclassifications (Empty)
        $response = $this->getJson(route('api.master.subclassifications.index'));
        $response->assertStatus(200)->assertJson([]);

        // 2. Create Subclassification
        $data = [
            'classification_id' => $classification->id,
            'nama' => 'Gedung'
        ];
        $response = $this->postJson(route('api.master.subclassifications.store'), $data);
        $response->assertStatus(201)
                 ->assertJsonFragment(['nama' => 'Gedung'])
                 ->assertJsonPath('classification.nama', 'SIPIL');
        $this->assertDatabaseHas('subclassifications', ['nama' => 'Gedung', 'classification_id' => $classification->id]);

        $subclassification = Subclassification::first();

        // 3. Update Subclassification
        $updateData = [
            'classification_id' => $classification->id,
            'nama' => 'Jalan'
        ];
        $response = $this->putJson(route('api.master.subclassifications.update', $subclassification->id), $updateData);
        $response->assertStatus(200)->assertJsonFragment(['nama' => 'Jalan']);
        $this->assertDatabaseHas('subclassifications', ['nama' => 'Jalan']);
        $this->assertDatabaseMissing('subclassifications', ['nama' => 'Gedung']);

        // 4. Delete Subclassification
        $response = $this->deleteJson(route('api.master.subclassifications.destroy', $subclassification->id));
        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertDatabaseMissing('subclassifications', ['nama' => 'Jalan']);
    }

    // ==========================================
    // Qualification API Tests
    // ==========================================
    public function test_can_manage_qualifications(): void
    {
        // 1. Get Qualifications (Empty)
        $response = $this->getJson(route('api.master.qualifications.index'));
        $response->assertStatus(200)->assertJson([]);

        // 2. Create Qualification
        $data = ['nama' => 'Ahli'];
        $response = $this->postJson(route('api.master.qualifications.store'), $data);
        $response->assertStatus(201)->assertJsonFragment(['nama' => 'Ahli']);
        $this->assertDatabaseHas('qualifications', ['nama' => 'Ahli']);

        $qualification = Qualification::first();

        // 3. Update Qualification
        $updateData = ['nama' => 'Terampil'];
        $response = $this->putJson(route('api.master.qualifications.update', $qualification->id), $updateData);
        $response->assertStatus(200)->assertJsonFragment(['nama' => 'Terampil']);
        $this->assertDatabaseHas('qualifications', ['nama' => 'Terampil']);
        $this->assertDatabaseMissing('qualifications', ['nama' => 'Ahli']);

        // 4. Delete Qualification
        $response = $this->deleteJson(route('api.master.qualifications.destroy', $qualification->id));
        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertDatabaseMissing('qualifications', ['nama' => 'Terampil']);
    }

    // ==========================================
    // Work Position API Tests
    // ==========================================
    public function test_can_manage_work_positions(): void
    {
        $classification = Classification::create(['nama' => 'SIPIL']);
        $subclassification = Subclassification::create([
            'classification_id' => $classification->id,
            'nama' => 'Gedung'
        ]);

        // 1. Get Work Positions (Empty)
        $response = $this->getJson(route('api.master.work-positions.index'));
        $response->assertStatus(200)->assertJson([]);

        // 2. Create Work Position
        $data = [
            'subclassification_id' => $subclassification->id,
            'kode_jabatan' => 'SI011002',
            'nama' => 'Manajer Lapangan Pelaksanaan Pekerjaan Gedung'
        ];
        $response = $this->postJson(route('api.master.work-positions.store'), $data);
        $response->assertStatus(201)
                 ->assertJsonFragment(['nama' => 'Manajer Lapangan Pelaksanaan Pekerjaan Gedung'])
                 ->assertJsonPath('subclassification.classification.nama', 'SIPIL');
        $this->assertDatabaseHas('work_positions', [
            'kode_jabatan' => 'SI011002',
            'nama' => 'Manajer Lapangan Pelaksanaan Pekerjaan Gedung'
        ]);

        $workPosition = WorkPosition::first();

        // 3. Update Work Position
        $updateData = [
            'subclassification_id' => $subclassification->id,
            'kode_jabatan' => 'SI011002',
            'nama' => 'Manajer Konstruksi Gedung'
        ];
        $response = $this->putJson(route('api.master.work-positions.update', $workPosition->id), $updateData);
        $response->assertStatus(200)->assertJsonFragment(['nama' => 'Manajer Konstruksi Gedung']);
        $this->assertDatabaseHas('work_positions', ['nama' => 'Manajer Konstruksi Gedung']);
        $this->assertDatabaseMissing('work_positions', ['nama' => 'Manajer Lapangan Pelaksanaan Pekerjaan Gedung']);

        // 4. Delete Work Position
        $response = $this->deleteJson(route('api.master.work-positions.destroy', $workPosition->id));
        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertDatabaseMissing('work_positions', ['nama' => 'Manajer Konstruksi Gedung']);
    }

    // ==========================================
    // LSP API Tests
    // ==========================================
    public function test_can_manage_lsps(): void
    {
        // 1. Get Lsps (Empty)
        $response = $this->getJson(route('api.master.lsps.index'));
        $response->assertStatus(200)->assertJson([]);

        // 2. Create Lsp
        $data = ['nama' => 'LSP ASTEKINDO'];
        $response = $this->postJson(route('api.master.lsps.store'), $data);
        $response->assertStatus(201)->assertJsonFragment(['nama' => 'LSP ASTEKINDO']);
        $this->assertDatabaseHas('lsps', ['nama' => 'LSP ASTEKINDO']);

        $lsp = Lsp::first();

        // 3. Update Lsp
        $updateData = ['nama' => 'LSP PETAKINDO'];
        $response = $this->putJson(route('api.master.lsps.update', $lsp->id), $updateData);
        $response->assertStatus(200)->assertJsonFragment(['nama' => 'LSP PETAKINDO']);
        $this->assertDatabaseHas('lsps', ['nama' => 'LSP PETAKINDO']);
        $this->assertDatabaseMissing('lsps', ['nama' => 'LSP ASTEKINDO']);

        // 4. Delete Lsp
        $response = $this->deleteJson(route('api.master.lsps.destroy', $lsp->id));
        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertDatabaseMissing('lsps', ['nama' => 'LSP PETAKINDO']);
    }

    // ==========================================
    // Association API Tests
    // ==========================================
    public function test_can_manage_associations(): void
    {
        // 1. Get Associations (Empty)
        $response = $this->getJson(route('api.master.associations.index'));
        $response->assertStatus(200)->assertJson([]);

        // 2. Create Association
        $data = ['nama' => 'ASTEKINDO'];
        $response = $this->postJson(route('api.master.associations.store'), $data);
        $response->assertStatus(201)->assertJsonFragment(['nama' => 'ASTEKINDO']);
        $this->assertDatabaseHas('associations', ['nama' => 'ASTEKINDO']);

        $association = Association::first();

        // 3. Update Association
        $updateData = ['nama' => 'GATAKI'];
        $response = $this->putJson(route('api.master.associations.update', $association->id), $updateData);
        $response->assertStatus(200)->assertJsonFragment(['nama' => 'GATAKI']);
        $this->assertDatabaseHas('associations', ['nama' => 'GATAKI']);
        $this->assertDatabaseMissing('associations', ['nama' => 'ASTEKINDO']);

        // 4. Delete Association
        $response = $this->deleteJson(route('api.master.associations.destroy', $association->id));
        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertDatabaseMissing('associations', ['nama' => 'GATAKI']);
    }

    // ==========================================
    // Cascade Delete Tests
    // ==========================================
    public function test_cascade_delete_relationships(): void
    {
        $classification = Classification::create(['nama' => 'SIPIL']);
        $subclassification = Subclassification::create([
            'classification_id' => $classification->id,
            'nama' => 'Gedung'
        ]);
        $workPosition = WorkPosition::create([
            'subclassification_id' => $subclassification->id,
            'kode_jabatan' => 'SI011002',
            'nama' => 'Manajer Lapangan Pelaksanaan Pekerjaan Gedung'
        ]);

        $this->assertDatabaseHas('classifications', ['id' => $classification->id]);
        $this->assertDatabaseHas('subclassifications', ['id' => $subclassification->id]);
        $this->assertDatabaseHas('work_positions', ['id' => $workPosition->id]);

        // Delete Classification
        $this->deleteJson(route('api.master.classifications.destroy', $classification->id));

        // Subclassification and WorkPosition should be automatically cascade-deleted
        $this->assertDatabaseMissing('classifications', ['id' => $classification->id]);
        $this->assertDatabaseMissing('subclassifications', ['id' => $subclassification->id]);
        $this->assertDatabaseMissing('work_positions', ['id' => $workPosition->id]);
    }
}


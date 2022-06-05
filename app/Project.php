<?php

namespace App;

// use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Project extends Model
{
    // use Searchable;
    use HasApiTokens;

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();

        $start_parts = explode('-', $this->contract_start);
        $end_parts = explode('-', $this->contract_end);


        $array['company'] = ($this->company ? $this->company->name : '');
        $array['clients'] = ($this->clients ? $this->clients->pluck('name')->toArray() : '');
        $array['members'] = ($this->members ? $this->members->pluck('name')->toArray() : '');
        $array['locations'] = ($this->locations ? $this->locations->pluck('name')->toArray() : '');
        $array['types'] = ($this->types ? $this->types->pluck('name')->toArray() : '');
        $array['methodologies'] = ($this->methodologies ? $this->methodologies->pluck('name')->toArray() : '');
        $array['topics'] = ($this->topics ? $this->topics->pluck('name')->toArray() : '');
        $array['start_year'] = ($this->contract_start ? $start_parts[0] : '');
        $array['end_year'] = ($this->contract_end ? $end_parts[0] : '');

        return array(
            'user_id' => $array['user_id'],
            'code' => $array['code'],
            'clients' => $array['clients'],
            'members' => $array['members'],
            'name_es' => $array['name_es'],
            'name_en' => $array['name_en'],
            'contract_name' => $array['contract_name'],
            'location' => $array['locations'],
            'project_type' => $array['types'],
            'methodologies' => $array['methodologies'],
            'topic' => $array['topics'],
            'company' => $array['company'],
            'duration' => $array['duration'],
            'amount' => (double) $array['amount'],
            'start_year' => (int) $array['start_year'],
            'end_year' => (int) $array['end_year'],
            'contract_start' => $array['contract_start'],
            'contract_end' => $array['contract_end'],
        );
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'code',
        'name_es',
        'name_en',
        'contract_name',
        'company_id',
        'duration',
        'location_id',
        'privacy_option_id',
        'project_id',
        'validity_of_confidentiality',
        'comments',
        'contract_id',
        'contract_creator',
        'contractual_object',
        'currency_id',
        'amount',
        'amount_mxn',
        'amount_usd',
        'contract_start',
        'contract_end',
        'description_es',
        'description_en',
        'short_description_es',
        'short_description_en',
        'interviews',
        'databases',
        'trainings',
        'study_cases',
        'rol',
        'mechanism_contract',
        'status','folio','date_contract','type_change','amount_before_iva','iva','currency_iva','data_web','file_web_single','project_resumen','project_title'
    ];

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function contract()
    {
        return $this->belongsTo('App\Contract');
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency');
    }

    public function files()
    {
        return $this->hasMany('App\ProjectFile');
    }

    public function linkeds()
    {
        return $this->hasMany('App\LinkedProject');
    }

    public function tematics_web()
    {
        return $this->hasMany('App\TematicWebProject');
    }

    public function consortiums()
    {
        return $this->hasMany('App\ConsortiumProject');
    }

    public function locations()
    {
        return $this->belongsToMany('App\Location', 'location_project', 'project_id', 'location_id');
    }

    public function methodologies()
    {
        return $this->belongsToMany('App\Methodology', 'methodology_project', 'project_id', 'methodology_id');
    }

    public function privacy_options()
    {
        return $this->belongsToMany('App\PrivacyOption', 'privacy_option_project', 'project_id', 'privacy_option_id');
    }

    public function publications()
    {
        return $this->hasMany('App\Publication');
    }

    public function satisfaction_documents()
    {
        return $this->belongsToMany('App\SatisfactionDocument', 'project_satisfaction_document', 'project_id', 'satisfaction_document_id');
    }

    public function topics()
    {
        return $this->belongsToMany('App\Topic', 'project_topic', 'project_id', 'topic_id');
    }

    public function types()
    {
        return $this->belongsToMany('App\Type');
    }

    // Stakeholder Model relationships

    public function clients()
    {
        return $this->belongsToMany('App\Stackeholder', 'client_project', 'project_id', 'stackeholder_id')->with('sh_type')->with('sh_description')->with('contacts');
    }

    public function funders()
    {
        return $this->belongsToMany('App\Stackeholder', 'funder_project', 'project_id', 'stackeholder_id')->with('sh_type')->with('sh_description')->with('contacts');
    }

    public function suppliers()
    {
        return $this->belongsToMany('App\Stackeholder', 'project_supplier', 'project_id', 'stackeholder_id')->with('sh_type')->with('sh_description')->with('contacts');
    }

    // User Model relationships

    public function contract_backed_staff()
    {
        return $this->belongsToMany('App\User', 'contract_backed_member_project', 'project_id', 'user_id')->with('role')->with('position');
    }

    public function members()
    {
        return $this->belongsToMany('App\User', 'member_project', 'project_id', 'user_id')->with('role')->with('position');
    }

    public function satisfactiondoc_backed_staff()
    {
        return $this->belongsToMany('App\User', 'satisfactiondoc_backed_member_project', 'project_id', 'user_id')->with('role')->with('position');
    }

    public function user()
    {
        return $this->belongsTo('App\User')->with('role')->with('position');
    }

    public function project_codes()
    {
        return $this->hasMany('App\ProjectCode');
    }

    public function project_document_names()
    {
        return $this->hasMany('App\DocumentTypesNamesProject');
    }


}

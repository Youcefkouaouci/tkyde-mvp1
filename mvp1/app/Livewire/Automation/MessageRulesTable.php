<?php

namespace App\Livewire\Automation;

use Closure;
use App\Models\Rule;
use App\Models\Channel;
use Livewire\Component;
use App\Models\Platform;
use Filament\Tables\Table;
use App\Models\RuleMessage;
use App\Models\MessageTemplate;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\ToggleButtons;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class MessageRulesTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $properties;
    public $selectedTemplate = null;
    public $isSmsSelected = false;

    protected $listeners = ['show-properties-modal' => 'showPropertiesModal'];

    public function showPropertiesModal($properties)
    {
        $this->properties = $properties;
        $this->dispatch('openModal', ['properties' => $properties]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Rule::query()
                    ->with(['properties.photos', 'properties.attribute', 'platforms', 'channels', 'ruleMessage'])
            )
            ->searchable()
            ->searchPlaceholder('Search messages...')
            ->columns([
                TextColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(fn(Rule $record) => $record->enabled)
                    ->formatStateUsing(function ($state) {
                        return $state 
                            ? '<div class="flex items-center"><div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center"><svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg></div></div>'
                            : '<div class="flex items-center"><div class="w-5 h-5 bg-red-500 rounded-full flex items-center justify-center"><svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg></div></div>';
                    })
                    ->html()
                    ->sortable('enabled'),
                TextColumn::make('name')
                    ->label('Message Name')
                    ->sortable()
                    ->searchable()
                    ->weight('medium'),

                TextColumn::make('sending_event')
                    ->label('Trigger')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function ($state) {
                        $eventMap = [
                            'booking_created' => '1 minute after booking',
                            'booking_updated' => '5 minutes after booking',
                            'booking_deleted' => '5 days before check-in',
                        ];
                        return $eventMap[$state] ?? $state;
                    }),

                TextColumn::make('hosts')
                    ->label('Hosts')
                    ->getStateUsing(function (Rule $record) {
                        // Simuler des hôtes pour la démo
                        return collect(['John Doe', 'Jane Smith', 'Mike Johnson'])
                            ->take(rand(1, 3))
                            ->map(function ($name, $index) {
                                $colors = ['bg-red-500', 'bg-blue-500', 'bg-pink-500', 'bg-green-500', 'bg-purple-500'];
                                $initials = collect(explode(' ', $name))->map(fn($n) => substr($n, 0, 1))->join('');
                                return '<div class="inline-flex items-center justify-center w-8 h-8 text-xs font-medium text-white rounded-full ' . $colors[$index % count($colors)] . '">' . $initials . '</div>';
                            })
                            ->join(' ');
                    })
                    ->html(),

                TextColumn::make('platforms')
                    ->label('Platforms')
                    ->getStateUsing(function (Rule $record) {
                        return $record->platforms->map(function ($platform) {
                            $platformIcons = [
                                'airbnb' => '<div class="inline-flex items-center justify-center w-8 h-8 bg-red-500 text-white rounded-full text-xs font-bold">A</div>',
                                'booking' => '<div class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full text-xs font-bold">B</div>',
                                'vrbo' => '<div class="inline-flex items-center justify-center w-8 h-8 bg-blue-400 text-white rounded-full text-xs font-bold">V</div>',
                                'default' => '<div class="inline-flex items-center justify-center w-8 h-8 bg-gray-500 text-white rounded-full text-xs font-bold">' . substr($platform->name, 0, 1) . '</div>',
                            ];
                            
                            $key = strtolower($platform->name);
                            return $platformIcons[$key] ?? $platformIcons['default'];
                        })->join(' ');
                    })
                    ->html(),
                TextColumn::make('properties')
                    ->label('Properties')
                    ->getStateUsing(function (Rule $record) {
                        return $record->properties->take(3)->map(function ($property, $index) {
                            $photo = $property->photos->first()?->url ?? 'https://images.pexels.com/photos/1396122/pexels-photo-1396122.jpeg?auto=compress&cs=tinysrgb&w=100&h=100&fit=crop';
                            return '<img src="' . $photo . '" alt="Property" class="inline-block w-10 h-10 rounded-lg object-cover border-2 border-white shadow-sm" style="margin-left: ' . ($index > 0 ? '-8px' : '0') . '">';
                        })->join('') . ($record->properties->count() > 3 ? '<span class="inline-flex items-center justify-center w-10 h-10 bg-gray-100 text-gray-600 rounded-lg text-xs font-medium border-2 border-white shadow-sm ml-2">+' . ($record->properties->count() - 3) . '</span>' : '');
                    })
                    ->html(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->paginationPageOptions([10, 25, 50])
            ->striped()
            ->filters([
                SelectFilter::make('enabled')
                    ->label('Status')
                    ->options([
                        true => 'Enabled',
                        false => 'Disabled',
                    ])
                    ->placeholder('All'),

                SelectFilter::make('sending_event')
                    ->label('Event')
                    ->options(Rule::pluck('sending_event', 'sending_event')->unique()->toArray())
                    ->placeholder('All'),
            ])
            ->actions([
                EditAction::make()
                    ->iconButton()
                    ->icon('heroicon-o-pencil')
                    ->color('info')
                    ->tooltip('Edit message')
                    ->slideOver()
                    ->form([
                        TextInput::make('name')
                            ->label('Rule Name')
                            ->required()
                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'Write the name of the rule ')
                            ->afterStateHydrated(fn($state, $set, Rule $record) => $set('name', $record->name)),

                        TextInput::make('sending_event')
                            ->label('Event')
                            ->required()
                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'Write a sending event')
                            ->afterStateHydrated(fn($state, $set, Rule $record) => $set('sending_event', $record->sending_event)),

                        TextInput::make('sending_minutes')
                            ->label('Minutes Before/After')
                            ->numeric()
                            ->required()
                            ->afterStateHydrated(fn($state, $set, Rule $record) => $set('sending_minutes', $record->sending_minutes)),

                        DateTimePicker::make('sending_time')
                            ->label('Sending Time')
                            ->required()
                            ->native(false)
                            ->seconds(false)
                            ->displayFormat('F j, Y, g:i a')
                            ->format('Y-m-d H:i:s')
                            ->default(fn($record) => $record->sending_time ?? now())
                            ->afterStateHydrated(fn($state, $set, Rule $record) => $set('sending_time', $record->sending_time)),

                        CheckboxList::make('properties')
                            ->columns(2)
                            ->required()
                            ->options(function () {
                                return Auth::user()
                                    ->ownedProperties()
                                    ->get()
                                    ->pluck('attribute.name', 'id')
                                    ->all();
                            })
                            ->default(function ($record) {
                                return $record->properties()->pluck('id')->toArray();
                            })
                            ->afterStateHydrated(fn($state, $set, Rule $rec) => $set('properties', $rec->properties()->pluck('properties.id')->toArray()))
                            ->searchable(fn(CheckboxList $component): bool => count($component->getOptions()) > 10)
                            ->bulkToggleable()
                            ->noSearchResultsMessage('No properties found.')
                            ->searchingMessage('Searching for a property...')
                            ->searchPrompt('Search for a property'),

                        CheckboxList::make('platforms')
                            ->required()
                            ->label('Platforms')
                            ->options(function () {
                                return Platform::whereIn('type', ['ota', 'pms', 'sub_pms'])
                                    ->pluck('name', 'platforms.id');
                            })
                            ->default(function ($record) {
                                return $record->platforms()->pluck('id')->toArray();
                            })
                            ->afterStateHydrated(fn($state, $set, Rule $rec) => $set('platforms', $rec->platforms()->pluck('platforms.id')->toArray()))
                            ->searchable(fn(CheckboxList $component): bool => count($component->getOptions()) > 5)
                            ->bulkToggleable()
                            ->noSearchResultsMessage('No platforms found.')
                            ->searchingMessage('Searching for a platform...')
                            ->searchPrompt('Search for a platform'),

                        Section::make('Channels')
                            ->description('Select the channels you want to apply this rule to.')
                            ->icon('heroicon-o-chat-bubble-left-right')
                            ->schema([
                                CheckboxList::make('channels')
                                    ->label('')
                                    ->options(function () {
                                        return Channel::all()
                                            ->pluck('name', 'id')
                                            ->all();
                                    })
                                    ->bulkToggleable()
                                    ->columns(2)
                                    ->reactive()
                                    ->afterStateHydrated(
                                        fn($state, $set, Rule $rec) =>
                                        $set('channels', $rec->channels()->select('channels.id')->pluck('channels.id')->toArray())
                                    )
                                    ->afterStateUpdated(function ($state, $set) {
                                        $isSmsSelected = in_array('sms', array_map(fn($item) => strtoupper($item), $state));
                                        $this->isSmsSelected = $isSmsSelected;
                                    }),
                            ])
                            ->collapsible(),

                        Section::make('Message')
                            ->description('Create the rule messages you want to use.')
                            ->icon('heroicon-o-envelope')
                            ->schema([
                                Select::make('locale')
                                    ->options([
                                        'en-US' => 'English (US)',
                                        'fr-FR' => 'French (France)',
                                    ])
                                    ->required()
                                    ->afterStateHydrated(fn($state, $set, Rule $rec) => $set('locale', $rec->ruleMessage?->locale ?? 'en-US')),

                                Select::make('template')
                                    ->label('Template')
                                    ->options(MessageTemplate::pluck('name', 'id')->toArray())
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, $set) {
                                        $template = MessageTemplate::find($state);
                                        if ($template) {
                                            $set('long_title', $template->long_title);
                                            $set('long_content', $template->long_content);
                                            $set('short_title', $template->short_title);
                                            $set('short_content', $template->short_content);
                                        }
                                    }),

                                Section::make('Long format')
                                    ->description('For Emails and API messages')
                                    ->schema([
                                        TextInput::make('long_title')
                                            ->autocomplete(false)
                                            ->maxLength(190)
                                            ->required()
                                            ->afterStateHydrated(fn($state, $set, Rule $rec) => $set('long_title', $rec->ruleMessage?->long_title ?? '')),
                                        Textarea::make('long_content')
                                            ->required()
                                            ->autosize()
                                            ->afterStateHydrated(fn($state, $set, Rule $rec) => $set('long_content', $rec->ruleMessage?->long_content ?? '')),
                                    ])
                                    ->collapsible(),
                                Section::make('Short format')
                                    ->description('For SMS messages')
                                    ->schema([
                                        TextInput::make('short_title')
                                            ->autocomplete(false)
                                            ->maxLength(190)
                                            ->afterStateHydrated(fn($state, $set, Rule $rec) => $set('short_title', $rec->ruleMessage?->short_title ?? '')),
                                        Textarea::make('short_content')
                                            ->autosize()
                                            ->maxLength(160)
                                            ->afterStateHydrated(fn($state, $set, Rule $rec) => $set('short_content', $rec->ruleMessage?->short_content ?? '')),
                                    ])
                                    ->disabled($this->isSmsSelected)
                                    ->collapsible()
                                    ->collapsed(),
                            ])
                            ->collapsible(),
                    ])

                    ->action(function ($data, $record) {
                        $record->update([
                            'name' => $data['name'],
                            'sending_event' => $data['sending_event'],
                            'sending_minutes' => $data['sending_minutes'],
                            'sending_time' => $data['sending_time'],
                        ]);

                        $rule_message = new RuleMessage([
                            'locale' => $data['locale'],
                            'short_title' => $data['short_title'] ?? '',
                            'long_title' => $data['long_title'],
                            'short_content' => $data['short_content'] ?? '',
                            'long_content' => $data['long_content']
                        ]);

                        $record->properties()->sync($data['properties']);
                        $record->platforms()->sync($data['platforms']);
                        $record->channels()->sync($data['channels']);

                        $rule_message->rule()->associate($record);
                        $rule_message->save();

                        return $record;
                    }),
                    
                DeleteAction::make()
                    ->iconButton()
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->tooltip('Delete message'),
            ])
            ->bulkActions([
                BulkAction::make('enable')
                    ->action(fn(Collection $records) => $records->each->update(['enabled' => true]))
                    ->label('Enable Selected')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->deselectRecordsAfterCompletion(),

                BulkAction::make('disable')
                    ->action(fn(Collection $records) => $records->each->update(['enabled' => false]))
                    ->label('Disable Selected')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->deselectRecordsAfterCompletion(),

                DeleteBulkAction::make()
            ])
            ->headerActions([
                CreateAction::make()
                    ->model(Rule::class)
                    ->modalHeading('Create a New Rule')
                    ->button()
                    ->label('Add New Rule')
                    ->icon('heroicon-c-plus')
                    ->slideOver()
                    ->form([
                        TextInput::make('name')
                            ->autocomplete(false)
                            ->required()
                            ->maxLength(190)
                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'Write name of a rule ')
                            ->placeholder('Write the rule you want to add'),

                        Select::make('sending_event')
                            ->label('Event')
                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: 'Write a sending event')
                            ->options([
                                'booking_created' => 'Booking Created',
                                'booking_updated' => 'Booking Updated',
                                'booking_deleted' => 'Booking Deleted',
                            ])
                            ->required(),

                        DateTimePicker::make('sending_time')
                            ->label('Sending Time')
                            ->required()
                            ->seconds(false)
                            ->displayFormat('F j, Y, g:i a')
                            ->format('Y-m-d H:i:s')
                            ->native(false)
                            ->default(null),

                        Section::make('properties')
                            ->description('Select the properties you want to apply this rule to.')
                            ->icon('heroicon-o-home')
                            ->schema([
                                CheckboxList::make('properties')
                                    ->columns(2)
                                    ->label('')
                                    ->options(function () {
                                        return Auth::user()
                                            ->ownedProperties()
                                            ->get()
                                            ->pluck('attribute.name', 'id')
                                            ->all();
                                    })
                                    ->searchable(fn(CheckboxList $component): bool => count($component->getOptions()) > 10)
                                    ->bulkToggleable()
                                    ->noSearchResultsMessage('No properties found.')
                                    ->searchingMessage('Searching for a property...')
                                    ->searchPrompt('Search for a property')
                            ])
                            ->collapsible(),

                        Section::make('Platforms')
                            ->description('Select the platforms you want to apply this rule to.')
                            ->icon('heroicon-o-presentation-chart-bar')
                            ->schema([
                                CheckboxList::make('platforms')
                                    ->label('')
                                    ->options(function () {
                                        return Platform::whereIn('type', ['ota', 'pms', 'sub_pms'])
                                            ->pluck('name', 'id');
                                    })
                                    ->bulkToggleable()
                                    ->columns(2)
                            ])
                            ->collapsible(),

                        Section::make('Channels')
                            ->description('Select the channels you want to apply this rule to.')
                            ->icon('heroicon-o-chat-bubble-left-right')
                            ->schema([
                                CheckboxList::make('channels')
                                    ->label('')
                                    ->options(function () {
                                        return Channel::all()
                                            ->pluck('name', 'id')
                                            ->all();
                                    })
                                    ->bulkToggleable()
                                    ->columns(2)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, $set) {
                                        $isSmsSelected = in_array('sms', array_map(fn($item) => strtoupper($item), $state));
                                        $this->isSmsSelected = $isSmsSelected;
                                    }),
                            ])
                            ->collapsible(),
                        Section::make('Message')
                            ->description('Create the rule messages you want to use.')
                            ->icon('heroicon-o-envelope')
                            ->schema([
                                Select::make('locale')
                                    ->options([
                                        // locales
                                        'en-US' => 'en_US'
                                    ])
                                    ->required(),
                                Select::make('template')
                                    ->label('Template')
                                    ->options(MessageTemplate::pluck('name', 'id')->toArray())
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, $set) {
                                        $template = MessageTemplate::find($state);
                                        if ($template) {
                                            $set('long_title', $template->long_title);
                                            $set('long_content', $template->long_content);
                                            $set('short_title', $template->short_title);
                                            $set('short_content', $template->short_content);
                                        }
                                    }),

                                Section::make('Long format')
                                    ->description('For Emails and API messages')
                                    ->schema([
                                        TextInput::make('long_title')
                                            ->autocomplete(false)
                                            ->maxLength(190)
                                            ->required(),
                                        Textarea::make('long_content')
                                            ->required()
                                            ->autosize(),
                                    ])
                                    ->collapsible(),
                                Section::make('Short format')
                                    ->description('For SMS messages')
                                    ->schema([
                                        TextInput::make('short_title')
                                            ->autocomplete(false)
                                            ->maxLength(190),
                                        Textarea::make('short_content')
                                            ->autosize()
                                            ->maxLength(160),
                                    ])
                                    ->disabled($this->isSmsSelected)
                                    ->collapsible()
                                    ->collapsed(),
                            ])
                            ->collapsible(),

                        ToggleButtons::make('enabled')
                            ->label('Enable this rule?')
                            ->boolean()
                            ->grouped()
                            ->default(true)
                    ])
                    ->using(function (array $data): Rule {
                        $rule = Rule::create([
                            'name' => $data['name'],
                            'enabled' => boolval($data['enabled']),
                            'sending_minutes' => 0,
                            'sending_offset_direction' => 'after',
                            'sending_event' => $data['sending_event'],
                            'sending_time' => $data['sending_time'],
                            'created_by' => Auth::user()->id,
                        ]);

                        $rule_message = new RuleMessage([
                            'locale' => $data['locale'],
                            'short_title' => $data['short_title'] ?? '',
                            'long_title' => $data['long_title'],
                            'short_content' => $data['short_content'] ?? '',
                            'long_content' => $data['long_content']
                        ]);

                        $rule->properties()->attach($data['properties']);
                        $rule->platforms()->attach($data['platforms']);
                        $rule->channels()->attach($data['channels']);

                        $rule_message->rule()->associate($rule);
                        $rule_message->save();



                        return $rule;
                    })
                    ->successNotification(null)
                    ->after(function () {
                        Notification::make()
                            ->body('New rule created successfully.')
                            ->success()
                            ->send();
                    })
            ])
            ->emptyStateHeading('No automated messages yet')
            ->emptyStateDescription('Create your first automated message to get started.')
            ->emptyStateIcon('heroicon-o-envelope');
    }

    public function render()
    {
        return view('livewire.automation.message-rules-table');
    }
}
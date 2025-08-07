<?php

namespace App\Filament\Clusters\Automation\Pages;

use App\Filament\Clusters\Automation;
use App\Models\Rule;
use App\Models\Channel;
use App\Models\Platform;
use App\Models\RuleMessage;
use App\Models\MessageTemplate;
use Filament\Pages\Page;
use Filament\Actions\CreateAction;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\DateTimePicker;

class Messages extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static string $view = 'filament.clusters.automation.pages.messages';
    protected static ?string $cluster = Automation::class;
    protected static ?int $navigationSort = 2;
    protected static ?string $title = 'All Messages';

    public function getExtraBodyAttributes(): array
    {
        return [
            'class' => 'no-title-page no-heading-page ',
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            '/automation' => 'Automation',
            '/automation/messages' => 'Automated Message', 
            '#' => 'All messages',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make('create-automated-message')
                ->label('+ Automated Message')
                ->button()
                ->color('primary')
                ->size('lg')
                ->slideOver()
                ->modalWidth('2xl')
                ->modalHeading('Create New Automated Message')
                ->extraAttributes([
                    'class' => 'bg-green-600 hover:bg-green-700 text-white font-medium px-6 py-3 rounded-lg shadow-sm transition-colors duration-200 inline-flex items-center'
                ])
                ->icon('heroicon-m-plus')
                ->form([
                    TextInput::make('name')
                        ->autocomplete(false)
                        ->required()
                        ->maxLength(190)
                        ->placeholder('Enter message name')
                        ->label('Message Name'),

                    Select::make('sending_event')
                        ->label('Trigger Event')
                        ->options([
                            'booking_created' => 'Booking Created',
                            'booking_updated' => 'Booking Updated',
                            'booking_deleted' => 'Booking Deleted',
                        ])
                        ->required()
                        ->placeholder('Select trigger event'),

                    DateTimePicker::make('sending_time')
                        ->label('Sending Time')
                        ->required()
                        ->seconds(false)
                        ->displayFormat('F j, Y, g:i a')
                        ->format('Y-m-d H:i:s')
                        ->native(false),

                    Section::make('Properties')
                        ->description('Select which properties this message applies to')
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
                                ->bulkToggleable()
                                ->searchable()
                        ])
                        ->collapsible(),

                    Section::make('Platforms')
                        ->description('Select which platforms this message applies to')
                        ->icon('heroicon-o-globe-alt')
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
                        ->description('Select which channels this message will be sent through')
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
                        ])
                        ->collapsible(),

                    Section::make('Message')
                        ->description('Configure your message content')
                        ->icon('heroicon-o-envelope')
                        ->schema([
                            Select::make('locale')
                                ->label('Language')
                                ->options(['en-US' => 'en_US'])
                                ->required(),

                            TextInput::make('long_title')
                                ->label('Title')
                                ->required()
                                ->maxLength(190)
                                ->placeholder('Enter message title'),

                            Textarea::make('long_content')
                                ->label('Content')
                                ->required()
                                ->autosize()
                                ->placeholder('Enter your message content...'),
                        ])
                        ->collapsible(),

                    ToggleButtons::make('enabled')
                        ->label('Enable this rule?')
                        ->boolean()
                        ->grouped()
                        ->default(true)
                ])
                ->action(function (array $data) {
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
                        'long_title' => $data['long_title'],
                        'long_content' => $data['long_content']
                    ]);

                    if (isset($data['properties'])) {
                        $rule->properties()->attach($data['properties']);
                    }
                    if (isset($data['platforms'])) {
                        $rule->platforms()->attach($data['platforms']);
                    }
                    if (isset($data['channels'])) {
                        $rule->channels()->attach($data['channels']);
                    }

                    $rule_message->rule()->associate($rule);
                    $rule_message->save();

                    Notification::make()
                        ->body('New rule created successfully.')
                        ->success()
                        ->send();
                })
        ];
    }

    public function getExtraBodyAttributes(): array
    {
        return [
            'class' => 'automation-messages-page',
        ];
    }
}
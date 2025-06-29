<?php

namespace App\Filament\Personal\Pages;

use App\Models\Branch;
use App\Models\Rank;
use App\Models\User;
use App\Rules\ValidDodId;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class ManageProfilePage extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected string $view = 'filament.personal.pages.manage-profile-page';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUser;

    protected static ?string $navigationLabel = 'Manage Profile';

    protected static ?string $title = 'Manage Profile';

    public ?array $data = [];

    public function mount(): void
    {
        $user = Auth::user();

        $this->form->fill([
            'avatar' => $user->avatar,
            'dodid' => $user->dodid,
            'email' => $user->email,
            'first_name' => $user->first_name,
            'middle_name' => $user->middle_name,
            'last_name' => $user->last_name,
            'nickname' => $user->nickname,
            'personal_phone' => $user->personal_phone,
            'personal_email' => $user->personal_email,
            'phone_numbers' => $user->phone_numbers,
            'emails' => $user->emails,
            'branch_id' => $user->branch_id,
            'rank_id' => $user->rank_id,
        ]);
    }

    protected function getSaveAction(): Action
    {
        return Action::make('save')
            ->label('Save Changes')
            ->disabled(fn () => $this->form->isDisabled())
            ->action(fn () => $this->submit());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Flex::make([
                    Fieldset::make()
                        ->contained(false)
                        ->schema([
                            FileUpload::make('avatar')
                                ->hiddenLabel()
                                ->image()
                                ->imageEditor()
                                ->avatar()
                                ->circleCropper()
                                ->nullable(),
                        ])->grow(false),
                    Fieldset::make()
                        ->contained(false)
                        ->columns(1)
                        ->schema([
                            Section::make('Personal Information')
                                ->columns(2)
                                ->collapsible()
                                ->schema([
                                    TextInput::make('dodid')
                                        ->label('DoD ID')
                                        ->disabled()
                                        ->readOnly()
                                        ->required()
                                        ->rules([
                                            new ValidDodId,
                                        ])->unique(ignoreRecord: true),
                                    TextInput::make('email')
                                        ->label('Official Email')
                                        ->disabled()
                                        ->readOnly()
                                        ->required()
                                        ->email()
                                        ->unique(ignoreRecord: true),
                                    TextInput::make('first_name')
                                        ->label('First Name')
                                        ->required(),
                                    TextInput::make('middle_name')
                                        ->label('Middle Name')
                                        ->nullable(),
                                    TextInput::make('last_name')
                                        ->label('Last Name')
                                        ->required(),
                                    TextInput::make('nickname')
                                        ->label('Nickname')
                                        ->nullable(),
                                    TextInput::make('personal_phone')
                                        ->mask('(999) 999-9999')
                                        ->label('Personal Phone')
                                        ->nullable()
                                        ->tel(),
                                    TextInput::make('personal_email')
                                        ->label('Personal Email')
                                        ->nullable()
                                        ->email(),
                                    $this->getSaveAction(),
                                ]),
                            Section::make('Additional Contact Information')
                                ->columns(2)
                                ->collapsible()
                                ->collapsed(true)
                                ->schema([
                                    Repeater::make('phone_numbers')
                                        ->label('Additional Phone Numbers')
                                        ->columns(2)
                                        ->addActionLabel('Add Phone Number')
                                        ->collapsible()
                                        ->reorderableWithButtons()
                                        ->defaultItems(0)
                                        ->schema([
                                            TextInput::make('label')
                                                ->label('Label')
                                                ->required(),
                                            TextInput::make('phone_number')
                                                ->label('Phone Number')
                                                ->tel()
                                                ->required(),
                                        ]),
                                    Repeater::make('emails')
                                        ->label('Additional Emails')
                                        ->columns(2)
                                        ->addActionLabel('Add Email')
                                        ->collapsible()
                                        ->reorderableWithButtons()
                                        ->defaultItems(0)
                                        ->schema([
                                            TextInput::make('label')
                                                ->label('Label')
                                                ->required(),
                                            TextInput::make('email')
                                                ->label('Email')
                                                ->email()
                                                ->required(),
                                        ]),
                                    $this->getSaveAction(),
                                ]),
                            Section::make('Military Information')
                                ->columns(2)
                                ->collapsible()
                                ->collapsed(true)
                                ->schema([
                                    Select::make('branch_id')
                                        ->label('Branch')
                                        ->options(Branch::all()->pluck('name', 'id'))
                                        ->required(),
                                    Select::make('rank_id')
                                        ->label('Rank')
                                        ->options(Rank::all()->pluck('name', 'id'))
                                        ->required(),
                                    $this->getSaveAction(),
                                ]),
                        ])->grow(true),
                ])->from('sm'),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $user->update($this->form->getState());

        Notification::make()
            ->title('Profile updated successfully')
            ->success()
            ->send();
    }
}

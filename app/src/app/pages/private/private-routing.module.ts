import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { PrivatePage } from './private.page';
import { PrivateGuard } from '../../guards/private/private.guard'
import { ResolverService } from '../../services/resolver/resolver.service'

const routes: Routes = [
  {
    path: 'board',
    component: PrivatePage,
    canActivate: [PrivateGuard],
    resolve: {
      userData: ResolverService
    },
    children: [
      {
        path: 'home',
        loadChildren: () => import('./home/home.module').then( m => m.HomePageModule)
      },
      {
        path: 'questions',
        loadChildren: () => import('./questions/questions.module').then( m => m.QuestionsPageModule)
      },
      {
        path: 'tags',
        loadChildren: () => import('./tags/tags.module').then( m => m.TagsPageModule)
      },
      {
        path: 'users',
        loadChildren: () => import('./users/users.module').then( m => m.UsersPageModule)
      },
      {
        path: 'profile/:id',
        loadChildren: () => import('./profile/profile.module').then( m => m.ProfilePageModule)
      },
      {
        path: 'edit-profile',
        loadChildren: () => import('./edit-profile/edit-profile.module').then( m => m.EditProfilePageModule)
      },
      {
        path: 'ask-question',
        loadChildren: () => import('./ask-question/ask-question.module').then( m => m.AskQuestionPageModule)
      },
      {
        path: 'show-question/:id',
        loadChildren: () => import('./show-question/show-question.module').then( m => m.ShowQuestionPageModule)
      },
      {
        path: 'message-box',
        loadChildren: () => import('./message-box/message-box.module').then( m => m.MessageBoxPageModule)
      },
      {
        path: 'message/:id/:name',
        loadChildren: () => import('./message/message.module').then( m => m.MessagePageModule)
      },
      {
        path: 'interest',
        loadChildren: () => import('./interest/interest.module').then( m => m.InterestPageModule)
      },
      {
        path: 'tag/:id/:name',
        loadChildren: () => import('./tag/tag.module').then( m => m.TagPageModule)
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class PrivatePageRoutingModule {}

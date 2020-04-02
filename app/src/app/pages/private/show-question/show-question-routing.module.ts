import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { ShowQuestionPage } from './show-question.page';

const routes: Routes = [
  {
    path: '',
    component: ShowQuestionPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class ShowQuestionPageRoutingModule {}

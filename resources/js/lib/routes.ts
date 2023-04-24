import {
  Home,
  Requests,
  NewRequests,
  Account,
  Login,
} from 'components/screens';
import { FunctionComponent } from 'react';
import i18n from 'translations';

const { t } = i18n;

interface IRoute {
  path: string;
  element: FunctionComponent;
  name: string;
  isInSidebar?: boolean;
  isPublic?: boolean;
}

export const routes: IRoute[] = [
  {
    path: '/',
    element: Home,
    name: t('home.screenTitle'),
    isInSidebar: true,
  },
  {
    path: '/requests',
    element: Requests,
    name: t('requests.screenTitle'),
    isInSidebar: true,
  },
  {
    path: '/new-requests',
    element: NewRequests,
    name: t('newRequest.screenTitle'),
    isInSidebar: true,
  },
  {
    path: '/account',
    element: Account,
    name: t('account.screenTitle'),
  },
  {
    path: '/login',
    element: Login,
    name: t('login.screenTitle'),
    isPublic: true,
  },
];

export default routes;

export const getIndexByPath = (_path: string) => {
  return routes.findIndex(({ path }) => path === _path);
};
